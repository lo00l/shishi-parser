<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_product', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->boolean('available')->default(true);
            $table->timestamps();
            $table->integer('original_id');
            $table->integer('page_id')->unsigned();
            $table->string('title');
            $table->string('russian_title');
            $table->integer('placeholder_image_width');
            $table->integer('placeholder_x1');
            $table->integer('placeholder_y1');
            $table->integer('placeholder_x2');
            $table->integer('placeholder_y2');
            $table->integer('title_image_width');
            $table->integer('title_x1');
            $table->integer('title_y1');
            $table->integer('title_x2');
            $table->integer('title_y2');
            $table->string('vendor_code');
            $table->text('help_html');
            $table->text('russian_help_html');
            $table->string('img');
            $table->foreign('page_id')->references('id')->on('af_page')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
