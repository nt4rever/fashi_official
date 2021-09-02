<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDealProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_deal_product', function (Blueprint $table) {
            $table->id('deal_id');
            $table->unsignedBigInteger('product_id');
            $table->text('deal_desc')->nullable();
            $table->string('deal_time');
            $table->timestamps();
            $table->foreign('product_id')->references('product_id')->on('tbl_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_deal_product');
    }
}
