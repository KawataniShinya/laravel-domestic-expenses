<?php

namespace App\Http\Services\DTO\Common;

class MemberCategoryHistory
{
    private int $member_category_history_id;
    private int $summary_ym;
    private int $member_id;
    private int $category_id;
    private string $category_name;
    private int $display_order;
    private bool $income_flg;
    private bool $del_flg;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $member_category_history_id,
        int $summary_ym,
        int $member_id,
        int $category_id,
        string $category_name,
        int $display_order,
        bool $income_flg,
        bool $del_flg,
        string $created_at,
        string $updated_at
    )
    {
        $this->member_category_history_id = $member_category_history_id;
        $this->summary_ym = $summary_ym;
        $this->member_id = $member_id;
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->display_order = $display_order;
        $this->income_flg = $income_flg;
        $this->del_flg = $del_flg;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return int
     */
    public function getMemberCategoryHistoryId(): int
    {
        return $this->member_category_history_id;
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
    public function getMemberId(): int
    {
        return $this->member_id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->display_order;
    }

    /**
     * @return bool
     */
    public function isIncomeFlg(): bool
    {
        return $this->income_flg;
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
