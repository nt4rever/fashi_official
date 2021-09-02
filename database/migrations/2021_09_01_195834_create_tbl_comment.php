<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_comment', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('customer_id');
            $table->text('comment_content');
            $table->string('comment_time');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('reply_id')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('tbl_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_comment');
    }
}
