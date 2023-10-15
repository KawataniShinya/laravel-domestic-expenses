<?php

namespace App\Http\Services\DTO\PaymentService;

class AuthMember
{
    private int $user_id;
    private string|null $email;
    private int|null $member_id;
    private string|null $member_name;
    private int|null $group_id;
    private string|null $group_name;

    public function __construct(
        int $user_id,
        string|null $email,
        int|null $member_id,
        string|null $member_name,
        int|null $group_id,
        string|null $group_name
    )
    {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->member_id = $member_id;
        $this->member_name = $member_name;
        $this->group_id = $group_id;
        $this->group_name = $group_name;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return int|null
     */
    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    /**
     * @return string|null
     */
    public function getMemberName(): ?string
    {
        return $this->member_name;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    /**
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->group_name;
    }
}
