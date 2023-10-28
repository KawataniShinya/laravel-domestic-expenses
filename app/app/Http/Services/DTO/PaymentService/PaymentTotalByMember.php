<?php

namespace App\Http\Services\DTO\PaymentService;

class PaymentTotalByMember
{
    private int $member_id;
    private int $amount;

    public function __construct(
        int $member_id,
        int $amount
    ) {
        $this->member_id = $member_id;
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
    public function getAmount(): int
    {
        return $this->amount;
    }
}
