<?php

namespace App\Http\Services\DTO\PaymentService;

class AuthMember
{
    private int $user_id;
    private string $email;
    private int $member_id;
    private string $member_name;
    private int $group_id;
    private string $group_name;

    public function __construct(
        int $user_id,
        string $email,
        int $member_id,
        string $member_name,
        int $group_id,
        string $group_name
    )
    {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->member_id = $member_id;
        $this->member_name = $member_name;
        $this->group_id = $group_id;
        $this->group_name = $group_name;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMemberId()
    {
        return $this->member_id;
    }

    public function getMemberName()
    {
        return $this->member_name;
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function getGroupName()
    {
        return $this->group_name;
    }
}
