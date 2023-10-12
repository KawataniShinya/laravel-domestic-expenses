<?php

namespace App\Http\Services;

class MemberServiceImpl implements MemberService
{
    private MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function getGroupIdByAuth()
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        return $authMember->group_id;
    }
}
