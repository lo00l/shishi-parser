<?php

namespace App\Console\Commands;

use App\AssetManager;
use App\Parsers\CategoryParser;
use App\Parsers\MainPageParser;
use App\Parsers\PageParser;
use App\Parsers\ProductParser;
use Illuminate\Console\Command;
use App\Category;
use App\Page;
use App\Product;
use App\ProductAttribute;
use App\Execution;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ParseCommand extends Command
{
    public $signature = 'parse';

    public $description = 'Parses catalog';

    public function handle()
    {
        $categoriesCount = 0;
        $pagesCount = 0;
        $productsCount = 0;

        $execution = new Execution();
        $execution->save();

//        DB::beginTransaction();

        $mainParser = new MainPageParser('/');
//        Log::info('Created MainPageParser');
        try {
            /**
             * @var \App\Category $category
             */
            foreach ($mainParser as $key => $category) {
                $categoriesCount++;
//                Log::info('Got Category ' . $category);
                $category->save();
                $categoryParser = new CategoryParser($category->getUrl());
//                Log::info('Created CategoryParser ' . $category->getUrl());
                /**
                 * @var Page $page
                 */
                foreach ($categoryParser as $pageKey => $page) {
                    $pagesCount++;
//                    Log::info('Got Page ' . $page);
                    $page->category()->associate($category);
                    $pageParser = new PageParser($page->getUrl());
//                    Log::info('Created PageParser ' . $page->getUrl());
                    $backgroundImgUrl = AssetManager::saveImg($pageParser->getBackgroundUrl());
                    $page->setAttribute('background_img', $backgroundImgUrl);
                    $page->setAttribute('background_width', $pageParser->getBackgroundWidth());
                    $page->setAttribute('background_height', $pageParser->getBackgroundHeight());
//                    Log::info('Updated page ' . $page);
                    $page->save();
                    /**
                     * @var \App\Product $product
                     */
                    foreach ($pageParser as $productKey => $product) {
                        $productsCount++;
//                        Log::info('Got Product ' . $product);
                        $product->page()->associate($page);
                        $product->save();
                        $productParser = new ProductParser($product);
//                        Log::info('Created ProductParser' . $product->getUrl());
                        $productParser->setProductDetailData();
//                        Log::info('Updated Product ' . $product);
                        $product->save();
                    }
                }
            }
        } catch (\Exception $e) {
//            DB::rollback();
            $execution->setAttribute('success', false);
            $execution->setAttribute('finished_at', \Carbon\Carbon::now());
            $execution->setAttribute('categories_count', $categoriesCount);
            $execution->setAttribute('pages_count', $pagesCount);
            $execution->setAttribute('products_count', $productsCount);
            $execution->save();
            exit();
        }

        $execution->setAttribute('finished_at', \Carbon\Carbon::now());
        $execution->setAttribute('categories_count', $categoriesCount);
        $execution->setAttribute('pages_count', $pagesCount);
        $execution->setAttribute('products_count', $productsCount);
        $execution->save();
//        DB::commit();

    }
}
