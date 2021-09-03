<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_email', 100);
            $table->string('customer_name', 100);
            $table->string('customer_password');
            $table->string('customer_address')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_image')->nullable();
            $table->string('customer_image_name')->nullable();
            $table->string('customer_token')->nullable();
            $table->tinyInteger('customer_status')->default(0);
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
        Schema::dropIfExists('tbl_customer');
    }
}
