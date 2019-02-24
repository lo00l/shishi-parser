<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_page', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->integer('original_id');
            $table->integer('category_id')->unsigned();
            $table->string('preview_img');
            $table->string('background_img');
            $table->integer('background_width');
            $table->integer('background_height');
            $table->foreign('category_id')->references('id')->on('af_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('af_page');
    }
}
