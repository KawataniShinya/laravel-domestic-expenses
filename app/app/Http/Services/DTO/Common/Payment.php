<?php

namespace App\Http\Services\DTO\Common;

class Payment
{
    private int $payment_id;
    private int $summary_ym;
    private int $group_id;
    private int $member_id;
    private int $category_id;
    private int|null $categorized_payment_id;
    private string|null $payment_date;
    private int $amount;
    private string|null $payment_label;
    private bool $del_flg;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $payment_id,
        int $summary_ym,
        int $group_id,
        int $member_id,
        int $category_id,
        int|null $categorized_payment_id,
        string|null $payment_date,
        int $amount,
        string|null $payment_label,
        bool $del_flg,
        string $created_at,
        string $updated_at
    )
    {
        $this->payment_id = $payment_id;
        $this->summary_ym = $summary_ym;
        $this->group_id = $group_id;
        $this->member_id = $member_id;
        $this->category_id = $category_id;
        $this->categorized_payment_id = $categorized_payment_id;
        $this->payment_date = $payment_date;
        $this->amount = $amount;
        $this->payment_label = $payment_label;
        $this->del_flg = $del_flg;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return int
     */
    public function getPaymentId(): int
    {
        return $this->payment_id;
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
     * @return int|null
     */
    public function getCategorizedPaymentId(): ?int
    {
        return $this->categorized_payment_id;
    }

    /**
     * @return string|null
     */
    public function getPaymentDate(): ?string
    {
        return $this->payment_date;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getPaymentLabel(): ?string
    {
        return $this->payment_label;
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
