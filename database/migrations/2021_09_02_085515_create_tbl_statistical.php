<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStatistical extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_statistical', function (Blueprint $table) {
            $table->id('id_statistical');
            $table->string('order_date');
            $table->string('sales');
            $table->string('profit')->nullable();
            $table->integer('quantity');
            $table->integer('total_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_statistical');
    }
}
