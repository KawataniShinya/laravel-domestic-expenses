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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id')->comment('収支明細ID');
            $table->integer('summary_ym')->comment('集計年月');
            $table->bigInteger('group_id')->unsigned()->comment('グループID');
            $table->bigInteger('member_id')->unsigned()->comment('メンバーID');
            $table->bigInteger('category_id')->unsigned()->comment('収支分類ID');
            $table->boolean('income_flg')->comment('収入フラグ');
            $table->bigInteger('categorized_payment_id')->unsigned()->nullable()->comment('収支分類別明細ID');
            $table->date('payment_date')->nullable()->comment('発生日付');
            $table->bigInteger('amount')->comment('金額');
            $table->string('payment_label')->nullable()->comment('収支名目');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->unique(['summary_ym', 'group_id', 'member_id', 'category_id', 'income_flg', 'categorized_payment_id'], 'payment_unique');
            $table->comment('収支明細');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
