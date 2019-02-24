<?php

namespace App\Console\Commands;

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

        DB::beginTransaction();


    }
}
