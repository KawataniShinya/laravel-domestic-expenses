<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberCategoryHistory;
use App\Http\Services\DTO\PaymentService\CategoryInGroup;

interface MemberCategoryRepository
{
    /**
     * @param int $summary_ym
     * @param array $memberArray
     * @return array<MemberCategoryHistory>
     */
    public function getMemberCategoryHistoriesByYmMembers(int $summary_ym, array $memberArray): array;

    /**
     * @param int $summary_ym
     * @param int $group_id
     * @return array<CategoryInGroup>
     */
    public function getMemberCategoryHistoriesByYmGroup(int $summary_ym, int $group_id): array;
}
