<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_user', function (Blueprint $table) {
            $table->id('id_roles_user');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('roles_id_roles');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('roles_id_roles')->references('id_roles')->on('tbl_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_user');
    }
}
