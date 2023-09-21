<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->insert([
            [
                'email' => 'test@test.com',
                'group_id' => 1,
                'member_name' => 'メンバーA',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'email' => null,
                'group_id' => 1,
                'member_name' => 'メンバーB',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'email' => null,
                'group_id' => 1,
                'member_name' => '共同口座',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'email' => null,
                'group_id' => 2,
                'member_name' => 'メンバーC',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'email' => null,
                'group_id' => null,
                'member_name' => 'メンバーD',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]
        ]);
    }
}
