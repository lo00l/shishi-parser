<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateParserExecutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_parser_execution', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('started_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('finished_at')->nullable();
            $table->boolean('success')->default(true);
            $table->integer('categories_count')->nullable();
            $table->integer('pages_count')->nullable();
            $table->integer('products_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('af_parser_execution');
    }
}
