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
            $table->id('category_id');
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('display_order')->unsigned()->nullable();
            $table->string('category_name');
            $table->boolean('del_flg')->default(false);
            $table->timestamps();

            $table->unique(['group_id', 'display_order']);
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
