<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_histories')->insert([
            [
                'summary_ym' => '202307',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 1,
                'member_name' => 'メンバーA',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'summary_ym' => '202307',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 2,
                'member_name' => 'メンバーB',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'summary_ym' => '202307',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 3,
                'member_name' => '共同口座',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'summary_ym' => '202308',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 1,
                'member_name' => 'メンバーA',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'summary_ym' => '202308',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 2,
                'member_name' => 'メンバーB',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'summary_ym' => '202308',
                'group_id' => 1,
                'group_name' => 'MyGroup',
                'member_id' => 3,
                'member_name' => '共同口座',
                'del_flg' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
