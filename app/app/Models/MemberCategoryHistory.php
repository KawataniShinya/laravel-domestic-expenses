<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MemberCategoryHistory
 *
 * @property int $member_category_history_id メンバー別収支分類履歴ID
 * @property int $summary_ym 集計年月
 * @property int $member_id メンバーID
 * @property int $category_id 収支分類ID
 * @property int $del_flg 削除フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MemberCategoryHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereMemberCategoryHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereSummaryYm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereUpdatedAt($value)
 * @property string $category_name 収支分類名
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereCategoryName($value)
 * @property int $income_flg 収入フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereIncomeFlg($value)
 * @property int $display_order グループ別表示順
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategoryHistory whereDisplayOrder($value)
 * @mixin \Eloquent
 */
class MemberCategoryHistory extends Model
{
    use HasFactory;
}
