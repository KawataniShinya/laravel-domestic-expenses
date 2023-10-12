<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberRepositoryImpl implements \App\Http\Services\MemberRepository
{
    public function selectMemberByAuth()
    {
        $email = Auth::user()->email;
        $authMember = User::leftJoin('members', function ($join) {
                $join
                    ->on('users.email', '=', 'members.email');
            })
            ->leftJoin('groups', function ($join) {
                $join
                    ->on('members.group_id', '=', 'groups.group_id');
            })
            ->where('members.del_flg', false)
            ->where('groups.del_flg', false)
            ->where('users.email', $email)
            ->selectRaw('
                users.id as user_id,
                users.email,
                members.member_id,
                members.member_name,
                groups.group_id,
                groups.group_name
            ')
            ->first();
        return $authMember;
    }
}
