<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name');
            $table->string('product_image');
            $table->text('product_desc')->nullable();
            $table->text('product_content')->nullable();
            $table->integer('product_price');
            $table->integer('product_price_discount')->nullable();
            $table->integer('product_quantity')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->tinyInteger('product_status')->default(0);
            $table->integer('product_sales_quantity')->default(0);
            $table->text('product_tag')->nullable();
            $table->string('product_slug');
            $table->integer('product_order')->default(0);
            $table->integer('product_views')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('tbl_category')->onDelete('cascade');
            $table->foreign('brand_id')->references('brand_id')->on('tbl_brand')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_product');
    }
}
