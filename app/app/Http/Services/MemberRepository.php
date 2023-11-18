<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberHistory;
use App\Http\Services\DTO\PaymentService\AuthMember;
use App\Http\Services\DTO\PaymentService\GroupMember;

interface MemberRepository
{
    /**
     * @return AuthMember
     */
    public function selectMemberByAuth() : AuthMember;

    /**
     * @param int $summary_ym
     * @param int $group_id
     * @return array<MemberHistory>
     */
    public function selectMemberHistoriesByGroupId(int $summary_ym, int $group_id): array;

    /**
     * @param int $group_id
     * @return array<GroupMember>
     */
    public function selectGroupMember(int $group_id): array;

    /**
     * @param array<MemberHistory> $memberHistories
     * @return void
     */
    public function insertMemberHistories(array $memberHistories): void;

    public function deleteMemberHistory(string $summary_ym, int $group_id): void;
}
