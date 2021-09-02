<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shipping_id');
            $table->tinyInteger('payment_id');
            $table->string('order_total');
            $table->tinyInteger('order_status')->default(0);
            $table->string('coupon_code')->nullable();
            $table->string('discount')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('shipping_id')->references('shipping_id')->on('tbl_shipping')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_order');
    }
}
