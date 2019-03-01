<?php

namespace App\Jobs;

use App\Category;
use App\Execution;
use App\IAssetManager;
use App\Page;
use App\Parsers\CategoryParser;
use App\Parsers\MainPageParser;
use App\Parsers\PageParser;
use App\Parsers\ProductParser;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParseJob extends Job
{
    protected $executionId;

    public function __construct($executionId = null)
    {
        $this->executionId = $executionId;
    }

    public function handle(IAssetManager $assetManager)
    {
        Log::info('Entering parse job');

        libxml_use_internal_errors(true);

        if (is_null($this->executionId)) {
            $execution = new Execution();
            $execution->save();
        } else {
            $execution = Execution::whereKey($this->executionId)->first();
        }
        if (is_null($execution)) {
            return;
        }

        Log::info("Start parsing execution #{$this->executionId}");

        $startTime = Carbon::now();

        $execution->setAttribute('started_at', $startTime);
        $execution->save();

        DB::beginTransaction();

        $categoriesCount = 0;
        $pagesCount = 0;
        $productsCount = 0;

        Log::info('Creating main parser');
        $mainParser = new MainPageParser('/', $assetManager);
        try {
            /**
             * @var \App\Category $category
             */
            foreach ($mainParser as $key => $category) {
                $categoriesCount++;
                $category->save();
                Log::info("Creating category parser with url {$category->getUrl()}");
                $categoryParser = new CategoryParser($category->getUrl(), $assetManager);
                /**
                 * @var Page $page
                 */
                foreach ($categoryParser as $pageKey => $page) {
                    $pagesCount++;
                    $page->category()->associate($category);
                    Log::info("Creating page parser with url {$page->getUrl()}");
                    $pageParser = new PageParser($page->getUrl(), $assetManager);
                    $backgroundImgUrl = $assetManager->saveImg($pageParser->getBackgroundUrl());
                    $page->setAttribute('background_img', $backgroundImgUrl);
                    $page->setAttribute('background_width', $pageParser->getBackgroundWidth());
                    $page->setAttribute('background_height', $pageParser->getBackgroundHeight());
                    $page->save();
                    /**
                     * @var \App\Product $product
                     */
                    foreach ($pageParser as $productKey => $product) {
                        $productsCount++;
                        $product->page()->associate($page);
                        $product->save();
                        $productParser = new ProductParser($product, $assetManager);
                        $productParser->setProductDetailData();
                        $product->save();
                        sleep(0.1);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            DB::rollback();
            $execution->setAttribute('success', false);
            $execution->setAttribute('finished_at', \Carbon\Carbon::now());
            $execution->setAttribute('categories_count', $categoriesCount);
            $execution->setAttribute('pages_count', $pagesCount);
            $execution->setAttribute('products_count', $productsCount);
            $execution->save();
            return;
        }

        Log::info("Finished parsing. Parsed $categoriesCount categories, $pagesCount pages, $productsCount products");
        $execution->setAttribute('success', true);
        $execution->setAttribute('finished_at', \Carbon\Carbon::now());
        $execution->setAttribute('categories_count', $categoriesCount);
        $execution->setAttribute('pages_count', $pagesCount);
        $execution->setAttribute('products_count', $productsCount);
        $execution->save();

        Log::info('Deleting items which has not been updated');
        $category = new Category();
        $page = new Page();
        $product = new Product();

        DB::table($category->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();
        DB::table($page->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();
        DB::table($product->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();

        DB::commit();

        Log::info('Exiting parse job');
    }
}
