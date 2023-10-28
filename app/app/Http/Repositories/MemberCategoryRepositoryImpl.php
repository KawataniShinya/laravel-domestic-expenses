<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\PaymentService\CategoryInGroup;
use App\Http\Services\MemberCategoryRepository;
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
            ->groupBy('category_id')
            ->selectRaw('category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
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
}
