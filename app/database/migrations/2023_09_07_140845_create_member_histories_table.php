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
        Schema::create('member_histories', function (Blueprint $table) {
            $table->id('member_history_id')->comment('メンバー履歴ID');
            $table->integer('summary_ym')->comment('集計年月');
            $table->bigInteger('group_id')->unsigned()->comment('グループID');
            $table->string('group_name')->comment('グループ名');
            $table->bigInteger('member_id')->unsigned()->comment('メンバーID');
            $table->string('member_name')->comment('メンバー名');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->unique(['summary_ym', 'group_id', 'member_id']);
            $table->comment('メンバー履歴');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_histories');
    }
};
