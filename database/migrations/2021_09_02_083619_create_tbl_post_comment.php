<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPostComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_post_comment', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('customer_id');
            $table->string('time');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('post_id')->on('tbl_post')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_post_comment');
    }
}
