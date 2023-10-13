<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\PaymentService\AuthMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberRepositoryImpl implements \App\Http\Services\MemberRepository
{
    public function selectMemberByAuth(): AuthMember
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

        $authMemberDTO = new AuthMember(
            $authMember->user_id,
            $authMember->email,
            $authMember->member_id,
            $authMember->member_name,
            $authMember->group_id,
            $authMember->group_name
        );

        return $authMemberDTO;
    }
}
