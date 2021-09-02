<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_post', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('post_title');
            $table->text('post_desc')->nullable();
            $table->text('post_content')->nullable();
            $table->text('post_meta_desc')->nullable();
            $table->string('post_meta_keyword')->nullable();
            $table->string('post_image');
            $table->tinyInteger('post_status')->default(0);
            $table->unsignedBigInteger('category_post_id');
            $table->string('post_slug');
            $table->integer('post_views')->default(0);
            $table->timestamps();
            $table->foreign('category_post_id')->references('category_post_id')->on('tbl_category_post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_post');
    }
}
