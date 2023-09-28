<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'group_id' => 1,
                'display_order' => 1,
                'category_name' => '拠出',
                'income_flg' => 1,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 2,
                'category_name' => '食料・日用品',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 3,
                'category_name' => '外食',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 4,
                'category_name' => '臨時出費',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 5,
                'category_name' => '住宅ローン',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 6,
                'category_name' => '水道',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 7,
                'category_name' => '電気・ガス',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'group_id' => 1,
                'display_order' => 8,
                'category_name' => 'インターネット・テレビ',
                'income_flg' => 0,
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
