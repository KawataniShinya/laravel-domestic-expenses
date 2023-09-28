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
        Schema::create('member_category_histories', function (Blueprint $table) {
            $table->id('member_category_history_id')->comment('メンバー別収支分類履歴ID');
            $table->integer('summary_ym')->comment('集計年月');
            $table->bigInteger('member_id')->unsigned()->comment('メンバーID');
            $table->bigInteger('category_id')->unsigned()->comment('収支分類ID');
            $table->string('category_name')->comment('収支分類名');
            $table->boolean('income_flg')->comment('収入フラグ');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->unique(['summary_ym', 'member_id', 'category_id'], 'ym_member_category_unique');
            $table->comment('メンバー別収支分類履歴');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_category_histories');
    }
};
