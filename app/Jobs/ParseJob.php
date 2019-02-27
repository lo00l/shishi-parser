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

class ParseJob extends Job
{
    protected $executionId;

    public function __construct($executionId = null)
    {
        $this->executionId = $executionId;
    }

    public function handle(IAssetManager $assetManager)
    {
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

        $startTime = Carbon::now();

        $execution->setAttribute('started_at', $startTime);
        $execution->save();

        DB::beginTransaction();

        $categoriesCount = 0;
        $pagesCount = 0;
        $productsCount = 0;

        $mainParser = new MainPageParser('/', $assetManager);
        try {
            /**
             * @var \App\Category $category
             */
            foreach ($mainParser as $key => $category) {
                $categoriesCount++;
                $category->save();
                $categoryParser = new CategoryParser($category->getUrl(), $assetManager);
                /**
                 * @var Page $page
                 */
                foreach ($categoryParser as $pageKey => $page) {
                    $pagesCount++;
                    $page->category()->associate($category);
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
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $execution->setAttribute('success', false);
            $execution->setAttribute('finished_at', \Carbon\Carbon::now());
            $execution->setAttribute('categories_count', $categoriesCount);
            $execution->setAttribute('pages_count', $pagesCount);
            $execution->setAttribute('products_count', $productsCount);
            $execution->save();
            return;
        }

        $execution->setAttribute('success', true);
        $execution->setAttribute('finished_at', \Carbon\Carbon::now());
        $execution->setAttribute('categories_count', $categoriesCount);
        $execution->setAttribute('pages_count', $pagesCount);
        $execution->setAttribute('products_count', $productsCount);
        $execution->save();

        $category = new Category();
        $page = new Page();
        $product = new Product();

        DB::table($category->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();
        DB::table($page->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();
        DB::table($product->getTable())->where('updated_at', '<', $startTime->toDateTimeString())->delete();

        DB::commit();
    }
}
