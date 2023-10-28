<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Member
 *
 * @property int $member_id メンバーID
 * @property string|null $email メールアドレス
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
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $fillable = [
        'email',
        'group_id',
        'member_name',
        'del_flg'
    ];
    protected $primaryKey = 'member_id';
}
