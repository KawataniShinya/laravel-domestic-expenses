<?php

namespace App\Http\Services;

interface MemberRepository
{
    public function selectMemberByAuth();
}
