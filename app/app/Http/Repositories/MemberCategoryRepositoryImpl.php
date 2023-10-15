<?php

namespace App\Http\Repositories;

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
}
