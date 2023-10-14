<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\PaymentService\AuthMember;
use App\Http\Services\MemberRepository;
use App\Models\MemberHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberRepositoryImpl implements MemberRepository
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

    public function selectMemberHistoriesByGroupId(int $summary_ym, int $group_id): array
    {
        $memberHistories = MemberHistory::where('summary_ym', $summary_ym)
            ->where('group_id', $group_id)
            ->orderBy('member_id')
            ->get();

        $memberHistoryArray = [];
        foreach ($memberHistories as $memberHistory) {
            $memberHistoryArray[] = new \App\Http\Services\DTO\Common\MemberHistory(
                $memberHistory->member_history_id,
                $memberHistory->summary_ym,
                $memberHistory->group_id,
                $memberHistory->group_name,
                $memberHistory->member_id,
                $memberHistory->member_name,
                $memberHistory->del_flg,
                $memberHistory->created_at,
                $memberHistory->updated_at
            );
        }

        return $memberHistoryArray;
    }
}
