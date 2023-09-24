<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class MemberByAuth implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $email = Auth::user()->email;
        $sql = '
            select
                users.id as user_id,
                users.email,
                members.member_id,
                members.member_name,
                groups.group_id,
                groups.group_name
            from
                users
                left join `members` on users.email = members.email
                left join `groups` on members.group_id = groups.group_id
            where
                members.del_flg = false and groups.del_flg = false
                and users.email = \'' . $email . '\'
        ';

        $builder->fromSub($sql, 'member_by_auth');
    }
}
