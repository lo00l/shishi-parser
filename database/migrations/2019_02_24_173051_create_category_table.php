<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_category', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->integer('original_id');
            $table->string('title');
            $table->string('russian_title')->nullable();
            $table->string('preview_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('af_category');
    }
}
