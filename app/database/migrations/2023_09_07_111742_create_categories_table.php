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
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id')->comment('カテゴリーID');
            $table->bigInteger('group_id')->unsigned()->nullable()->comment('グループID');
            $table->bigInteger('display_order')->unsigned()->nullable()->comment('グループ別表示順');
            $table->string('category_name')->comment('カテゴリー名');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->unique(['group_id', 'display_order']);
            $table->comment('カテゴリー');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
