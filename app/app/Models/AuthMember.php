<?php

namespace App\Models;

use App\Models\Scopes\MemberByAuth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMember extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new MemberByAuth());
    }
}
