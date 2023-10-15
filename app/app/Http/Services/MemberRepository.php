<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberHistory;
use App\Http\Services\DTO\PaymentService\AuthMember;

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
}
