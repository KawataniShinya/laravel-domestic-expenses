<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\PaymentService\AuthMember;
use App\Http\Services\DTO\PaymentService\GroupMember;
use App\Http\Services\MemberRepository;
use App\Models\Member;
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

    public function selectGroupMember(int $group_id): array
    {
        $groupMembers = Member::leftJoin('groups', function ($join) {
                $join
                    ->on('members.group_id', '=', 'groups.group_id');
            })
            ->where('members.group_id', $group_id)
            ->where('members.del_flg', false)
            ->where('groups.del_flg', false)
            ->selectRaw(
                '
                        groups.group_id as group_id,
                        groups.group_name as group_name,
                        members.member_id as member_id,
                        members.member_name as member_name
                    '
            )
            ->get();

        $groupMemberArray = [];
        foreach ($groupMembers as $groupMember) {
            $groupMemberArray[] = new GroupMember(
                $groupMember->group_id,
                $groupMember->group_name,
                $groupMember->member_id,
                $groupMember->member_name
            );
        }

        return $groupMemberArray;
    }

    public function insertMemberHistories(array $memberHistories): void
    {
        $memberHistoryArray = [];
        foreach ($memberHistories as $memberHistory) {
            $memberHistoryArray[] = [
                'summary_ym' => $memberHistory->getSummaryYm(),
                'group_id' => $memberHistory->getGroupId(),
                'group_name' => $memberHistory->getGroupName(),
                'member_id' => $memberHistory->getMemberId(),
                'member_name' => $memberHistory->getMemberName(),
                'del_flg' => $memberHistory->isDelFlg(),
                'created_at' => $memberHistory->getCreatedAt(),
                'updated_at' => $memberHistory->getUpdatedAt()
            ];
        }

        MemberHistory::insert($memberHistoryArray);
    }
}
