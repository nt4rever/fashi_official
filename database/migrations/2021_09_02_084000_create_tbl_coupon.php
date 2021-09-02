<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon', function (Blueprint $table) {
            $table->id('coupon_id');
            $table->string('coupon_name');
            $table->string('coupon_code');
            $table->integer('coupon_time');
            $table->integer('coupon_number');
            $table->tinyInteger('coupon_condition');
            $table->timestamp('coupon_start')->nullable();
            $table->timestamp('coupon_end')->nullable();
            $table->tinyInteger('coupon_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_coupon');
    }
}
