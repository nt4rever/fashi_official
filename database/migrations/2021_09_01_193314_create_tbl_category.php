<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_category', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->text('category_desc')->nullable();
            $table->tinyInteger('category_status')->default(0);
            $table->string('category_image')->nullable();
            $table->string('category_image_name')->nullable();
            $table->integer('category_parentId')->default(0);
            $table->tinyInteger('role')->default(0);
            $table->string('category_slug');
            $table->integer('category_order')->default(0);
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
        Schema::dropIfExists('tbl_category');
    }
}
