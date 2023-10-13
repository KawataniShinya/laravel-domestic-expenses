<?php

namespace App\Http\Services;

use App\Http\Services\DTO\PaymentService\AuthMember;

interface MemberRepository
{
    /**
     * @return AuthMember
     */
    public function selectMemberByAuth() : AuthMember;
}
