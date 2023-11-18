<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MemberCategory
 *
 * @property int $member_category_id メンバー別収支分類ID
 * @property int $member_id メンバーID
 * @property int $category_id 収支分類ID
 * @property int $del_flg 削除フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MemberCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereMemberCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MemberCategory extends Model
{
    use HasFactory;

    protected $table = 'member_categories';
    protected $fillable = [
        'member_id',
        'category_id',
        'del_flg'
    ];
    protected $primaryKey = 'member_category_id';
}
