<?php

namespace App\Http\Services\DTO\PaymentService;

class GroupMember
{
    private int $group_id;
    private string $group_name;
    private int $member_id;
    private string $member_name;

    public function __construct(
        int $group_id,
        string $group_name,
        int $member_id,
        string $member_name
    ) {
        $this->group_id = $group_id;
        $this->group_name = $group_name;
        $this->member_id = $member_id;
        $this->member_name = $member_name;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->group_id;
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->group_name;
    }

    /**
     * @return int
     */
    public function getMemberId(): int
    {
        return $this->member_id;
    }

    /**
     * @return string
     */
    public function getMemberName(): string
    {
        return $this->member_name;
    }
}
