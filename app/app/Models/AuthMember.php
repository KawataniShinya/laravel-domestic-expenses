<?php

namespace App\Models;

use App\Models\Scopes\MemberByAuth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\AuthMember
 *
 * @method static Builder|AuthMember groupMemberHistories(\phpDocumentor\Reflection\Types\Integer $group_id)
 * @method static Builder|AuthMember newModelQuery()
 * @method static Builder|AuthMember newQuery()
 * @method static Builder|AuthMember query()
 * @mixin \Eloquent
 */
class AuthMember extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new MemberByAuth());
    }

    public function scopeGroupMemberHistories(Integer $summary_ym, Integer $group_id)
    {
        $sql = '
            select
                members.member_id,
                members.member_name
            from
                member_histories
                left join `groups` on members.group_id = `groups`.group_id
            where
                `groups`.group_id = ' . $group_id . '
        ';

        return DB::query($sql);
    }
}
