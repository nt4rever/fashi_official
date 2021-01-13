<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Fulltext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            DB::statement('ALTER TABLE tbl_product ADD FULLTEXT `product_name` (`product_name`)');
            DB::statement('ALTER TABLE tbl_product ENGINE = MyISAM'); // đánh index theo kiểu MyISam ngoài ra còn có kiểu InnoDB nếu không có dòng này cũng được mysql sẽ mặc định là index kiểu MyISAM nhé
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE tbl_product DROP INDEX product_name'); // khi chạy lệnh rollback thì làm điều ngược lại với thằng trên thế thôi
        });
    }
}
