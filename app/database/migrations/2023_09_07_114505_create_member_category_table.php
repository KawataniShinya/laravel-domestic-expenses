<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_category', function (Blueprint $table) {
            $table->id('member_category_id')->comment('メンバー別収支分類ID');
            $table->bigInteger('member_id')->unsigned()->nullable()->comment('メンバーID');
            $table->bigInteger('category_id')->unsigned()->nullable()->comment('収支分類ID');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->unique(['member_id', 'category_id']);
            $table->comment('メンバー別収支分類');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_category');
    }
};
