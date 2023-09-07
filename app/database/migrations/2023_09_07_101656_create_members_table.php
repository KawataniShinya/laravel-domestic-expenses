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
        Schema::create('members', function (Blueprint $table) {
            $table->id('member_id')->comment('メンバーID');
            $table->bigInteger('user_id')->unsigned()->nullable()->comment('ユーザーID');
            $table->bigInteger('group_id')->unsigned()->nullable()->comment('グループID');
            $table->string('member_name')->comment('メンバー名');
            $table->boolean('del_flg')->default(false)->comment('削除フラグ');
            $table->timestamps();

            $table->comment('メンバー');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
