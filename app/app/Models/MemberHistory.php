<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MemberHistory
 *
 * @property int $member_history_id メンバー履歴ID
 * @property int $summary_ym 集計年月
 * @property int $group_id グループID
 * @property string $group_name グループ名
 * @property int $member_id メンバーID
 * @property string $member_name メンバー名
 * @property int $del_flg 削除フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MemberHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereMemberHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereMemberName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereSummaryYm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MemberHistory extends Model
{
    use HasFactory;

    protected $table = 'member_histories';
    protected $fillable = [
        'summary_ym',
        'group_id',
        'group_name',
        'member_id',
        'member_name',
        'del_flg'
    ];
    protected $primaryKey = 'member_history_id';
}
