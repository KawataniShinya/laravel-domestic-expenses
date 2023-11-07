<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\PaymentService\CategoryInGroup;
use App\Http\Services\DTO\PaymentService\MemberCategoryForHistory;
use App\Http\Services\MemberCategoryRepository;
use App\Models\MemberCategory;
use App\Models\MemberCategoryHistory;

class MemberCategoryRepositoryImpl implements MemberCategoryRepository
{
    public function getMemberCategoryHistoriesByYmMembers(int $summary_ym, array $memberArray): array
    {
        $memberCategoryHistories = MemberCategoryHistory::where('summary_ym', $summary_ym)
            ->whereIn('member_id', $memberArray)
            ->orderByRaw('member_id, category_id, display_order')
            ->get();

        $memberCategoryHistoryArray = [];
        foreach ($memberCategoryHistories as $memberCategoryHistory) {
            $memberCategoryHistoryArray[] = new \App\Http\Services\DTO\Common\MemberCategoryHistory(
                $memberCategoryHistory->member_category_history_id,
                $memberCategoryHistory->summary_ym,
                $memberCategoryHistory->member_id,
                $memberCategoryHistory->category_id,
                $memberCategoryHistory->category_name,
                $memberCategoryHistory->display_order,
                $memberCategoryHistory->income_flg,
                $memberCategoryHistory->del_flg,
                $memberCategoryHistory->created_at,
                $memberCategoryHistory->updated_at
            );
        }

        return $memberCategoryHistoryArray;
    }

    public function getMemberCategoryHistoriesByYmGroup(int $summary_ym, int $group_id): array
    {
        $memberCategoryHistories = MemberCategoryHistory::leftJoin('member_histories', function ($join) {
                $join
                    ->on('member_category_histories.member_id', '=', 'member_histories.member_id');
            })
            ->where('member_category_histories.summary_ym', $summary_ym)
            ->where('member_histories.summary_ym', $summary_ym)
            ->where('member_histories.group_id', $group_id)
            ->groupBy('member_category_histories.category_id')
            ->selectRaw('member_category_histories.category_id, max(member_category_histories.category_name) as category_name, max(member_category_histories.display_order) as display_order, max(member_category_histories.income_flg) as income_flg')
            ->orderBy('display_order')
            ->get();

        $memberCategoryHistoryArray = [];
        foreach ($memberCategoryHistories as $memberCategoryHistory) {
            $memberCategoryHistoryArray[] = new CategoryInGroup(
                $memberCategoryHistory->category_id,
                $memberCategoryHistory->category_name,
                $memberCategoryHistory->display_order,
                $memberCategoryHistory->income_flg
            );
        }

        return $memberCategoryHistoryArray;
    }

    public function selectMemberCategoryHistoriesByGroupId(int $summary_ym, int $group_id): array
    {
        $memberCategoryHistories = MemberCategoryHistory::leftJoin('member_histories', function ($join) {
                $join
                    ->on('member_category_histories.member_id', '=', 'member_histories.member_id');
            })
            ->where('member_category_histories.summary_ym', $summary_ym)
            ->where('member_histories.summary_ym', $summary_ym)
            ->where('member_histories.group_id', $group_id)
            ->selectRaw('
                    max(member_category_histories.member_category_history_id) as member_category_history_id,
                    member_category_histories.member_id,
                    member_category_histories.category_id,
                    max(member_category_histories.category_name) as category_name,
                    max(member_category_histories.display_order) as display_order,
                    max(member_category_histories.income_flg) as income_flg,
                    max(member_category_histories.del_flg) as del_flg,
                    max(member_category_histories.created_at) as created_at,
                    max(member_category_histories.updated_at) as updated_at
                ')
            ->groupByRaw('member_id, category_id')
            ->orderByRaw('member_id, category_id, display_order')
            ->get();

        $memberCategoryForHistoryArray = [];
        foreach ($memberCategoryHistories as $memberCategoryHistory) {
            $memberCategoryForHistoryArray[] = new \App\Http\Services\DTO\Common\MemberCategoryHistory(
                $memberCategoryHistory->member_category_history_id,
                $summary_ym,
                $memberCategoryHistory->member_id,
                $memberCategoryHistory->category_id,
                $memberCategoryHistory->category_name,
                $memberCategoryHistory->display_order,
                $memberCategoryHistory->income_flg,
                $memberCategoryHistory->del_flg,
                $memberCategoryHistory->created_at,
                $memberCategoryHistory->updated_at,
            );
        }

        return $memberCategoryForHistoryArray;
    }

    public function selectMemberCategoriesByMembersForHistory(array $groupMembers): array
    {
        $memberIDs = [];
        foreach ($groupMembers as $groupMember) {
            $memberIDs[] = $groupMember->getMemberId();
        }

        $memberCategories = MemberCategory::leftJoin('categories', function ($join) {
                $join
                    ->on('member_categories.category_id', '=', 'categories.category_id');
            })
            ->whereIn('member_categories.member_id', $memberIDs)
            ->where('member_categories.del_flg', false)
            ->where('categories.del_flg', false)
            ->selectRaw(
                '
                        member_categories.member_id as member_id,
                        member_categories.category_id as category_id,
                        categories.category_name as category_name,
                        categories.display_order as display_order,
                        categories.income_flg as income_flg
                    '
            )
            ->get();

        $memberCategoryForHistoryArray = [];
        foreach ($memberCategories as $memberCategory) {
            $memberCategoryForHistoryArray[] = new MemberCategoryForHistory(
                $memberCategory->member_id,
                $memberCategory->category_id,
                $memberCategory->category_name,
                $memberCategory->display_order,
                $memberCategory->income_flg
            );
        }

        return $memberCategoryForHistoryArray;
    }

    public function insertMemberCategoryHistories(array $memberCategoryHistories): void
    {
        $memberCategoryHistoryArray = [];
        foreach ($memberCategoryHistories as $memberCategoryHistory) {
            $memberCategoryHistoryArray[] = [
                "summary_ym" => $memberCategoryHistory->getSummaryYm(),
                "member_id" => $memberCategoryHistory->getMemberId(),
                "category_id" => $memberCategoryHistory->getCategoryId(),
                "category_name" => $memberCategoryHistory->getCategoryName(),
                "display_order" => $memberCategoryHistory->getDisplayOrder(),
                "income_flg" => $memberCategoryHistory->isIncomeFlg(),
                "del_flg" => $memberCategoryHistory->isDelFlg(),
                "created_at" => $memberCategoryHistory->getCreatedAt(),
                "updated_at" => $memberCategoryHistory->getUpdatedAt()
            ];
        }

        MemberCategoryHistory::insert($memberCategoryHistoryArray);
    }
}
