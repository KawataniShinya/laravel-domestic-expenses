<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberCategoryHistory;

interface MemberCategoryRepository
{
    /**
     * @param int $summary_ym
     * @param array $memberArray
     * @return array<MemberCategoryHistory>
     */
    public function getMemberCategoryHistoriesByYmMembers(int $summary_ym, array $memberArray): array;
}
