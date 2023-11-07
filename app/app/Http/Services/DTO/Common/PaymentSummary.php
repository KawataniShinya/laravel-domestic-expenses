<?php

namespace App\Http\Services\DTO\Common;

class PaymentSummary
{
    private int $member_id;
    private int $category_id;
    private int $amount;

    public function __construct(
        int $member_id,
        int $category_id,
        int $amount
    ) {
        $this->member_id = $member_id;
        $this->category_id = $category_id;
        $this->amount = $amount;
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
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
