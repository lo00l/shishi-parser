<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('af_product_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('active')->default(true);
            $table->integer('product_id')->unsigned();
            $table->string('title');
            $table->string('russian_title')->nullable();
            $table->string('value');
            $table->string('russian_value')->nullable();
            $table->foreign('product_id')->references('id')->on('af_product')->onDelete('cascade');
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
