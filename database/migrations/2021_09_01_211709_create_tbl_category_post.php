<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCategoryPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_category_post', function (Blueprint $table) {
            $table->id('category_post_id');
            $table->string('category_post_name');
            $table->tinyInteger('category_post_status')->default(0);
            $table->string('category_post_slug');
            $table->string('category_post_desc')->nullable();
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
        Schema::dropIfExists('tbl_category_post');
    }
}
