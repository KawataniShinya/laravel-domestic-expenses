<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Member
 *
 * @property int $member_id メンバーID
 * @property int|null $user_id ユーザーID
 * @property int|null $group_id グループID
 * @property string $member_name メンバー名
 * @property int $del_flg 削除フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUserId($value)
 * @property string|null $email メールアドレス
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member groupMembers(int $groupId)
 * @mixin \Eloquent
 */
class Member extends Model
{
    use HasFactory;

    public function scopeGroupMembers($query, int $groupId)
    {
        return Member::leftJoin('groups', function ($join) {
            $join
                ->on('members.group_id', '=', 'groups.group_id');
        })
            ->where('members.group_id', $groupId)
            ->where('members.del_flg', false)
            ->where('groups.del_flg', false)
            ->selectRaw(
                '
                        groups.group_id as group_id,
                        groups.group_name as group_name,
                        members.member_id as member_id,
                        members.member_name as member_name
                    '
            );
    }
}
