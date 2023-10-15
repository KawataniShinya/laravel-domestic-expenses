<?php

namespace App\Http\Services\DTO\Common;

class MemberHistory
{
    private int $member_history_id;
    private int $summary_ym;
    private int $group_id;
    private string $group_name;
    private int $member_id;
    private string $member_name;
    private bool $del_flg;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $member_history_id,
        int $summary_ym,
        int $group_id,
        string $group_name,
        int $member_id,
        string $member_name,
        bool $del_flg,
        string $created_at,
        string $updated_at
    )
    {
        $this->member_history_id = $member_history_id;
        $this->summary_ym = $summary_ym;
        $this->group_id = $group_id;
        $this->group_name = $group_name;
        $this->member_id= $member_id;
        $this->member_name = $member_name;
        $this->del_flg = $del_flg;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return int
     */
    public function getMemberHistoryId(): int
    {
        return $this->member_history_id;
    }

    /**
     * @return int
     */
    public function getSummaryYm(): int
    {
        return $this->summary_ym;
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

    /**
     * @return bool
     */
    public function isDelFlg(): bool
    {
        return $this->del_flg;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
}
