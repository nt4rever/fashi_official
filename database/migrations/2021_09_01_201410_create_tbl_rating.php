<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_rating', function (Blueprint $table) {
            $table->id('rating_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->tinyInteger('rating');
            $table->timestamps();
            $table->foreign('product_id')->references('product_id')->on('tbl_product')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_rating');
    }
}
