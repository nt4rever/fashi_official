<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblHomeSlider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_home_slider', function (Blueprint $table) {
            $table->id('home_slider_id');
            $table->string('home_slider_image');
            $table->string('home_slider_image_name')->nullable();
            $table->text('home_slider_desc')->nullable();
            $table->string('home_slider_sale');
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
        Schema::dropIfExists('tbl_home_slider');
    }
}
