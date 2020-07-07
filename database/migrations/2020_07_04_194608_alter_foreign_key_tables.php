<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeignKeyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
        });
        Schema::table('category', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('category');
        });
        Schema::table('comment', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete();
        });
        Schema::table('product', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('category_id')->references('id')->on('category');

        });
        Schema::table('user_role', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('role_id')->references('id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
