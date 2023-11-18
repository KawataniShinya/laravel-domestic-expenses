<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberCategoryHistory;
use App\Http\Services\DTO\PaymentService\CategoryInGroup;
use App\Http\Services\DTO\PaymentService\GroupMember;
use App\Http\Services\DTO\PaymentService\MemberCategoryForHistory;

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

    /**
     * @param int $summary_ym
     * @param int $group_id
     * @return array<MemberCategoryHistory>
     */
    public function selectMemberCategoryHistoriesByGroupId(int $summary_ym, int $group_id): array;

    /**
     * @param array<GroupMember> $groupMembers
     * @return array<MemberCategoryForHistory>
     */
    public function selectMemberCategoriesByMembersForHistory(array $groupMembers): array;

    /**
     * @param array<MemberCategoryHistory> $memberCategoryHistories
     * @return void
     */
    public function insertMemberCategoryHistories(array $memberCategoryHistories): void;

    public function deleteMemberCategoryHistory(string $summary_ym, array $memberIDs): void;
}
