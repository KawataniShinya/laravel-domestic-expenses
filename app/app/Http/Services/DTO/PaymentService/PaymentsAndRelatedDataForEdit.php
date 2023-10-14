<?php

namespace App\Http\Services\DTO\PaymentService;

use App\Http\Services\DTO\Common\MemberCategoryHistory;
use App\Http\Services\DTO\Common\MemberHistory;
use App\Http\Services\DTO\Common\Payment;

class PaymentsAndRelatedDataForEdit
{
    /**
     * @var array<MemberHistory>
     */
    private array $memberHistories;

    /**
     * @var array<MemberCategoryHistory>
     */
    private array $memberCategoryHistories;

    /**
     * @var array<Payment>
     */
    private array $payments;

    /**
     * @param array<MemberHistory> $memberHistories
     * @param array<MemberCategoryHistory> $memberCategoryHistories
     * @param array<PaymentForEdit> $payments
     */
    public function __construct(
        array $memberHistories,
        array $memberCategoryHistories,
        array $payments
    )
    {
        $this->memberHistories = $memberHistories;
        $this->memberCategoryHistories = $memberCategoryHistories;
        $this->payments = $payments;
    }

    /**
     * @return array<MemberHistory>
     */
    public function getMemberHistories(): array
    {
        return $this->memberHistories;
    }

    /**
     * @return array<MemberCategoryHistory>
     */
    public function getMemberCategoryHistories(): array
    {
        return $this->memberCategoryHistories;
    }

    /**
     * @return array<Payment>
     */
    public function getPayments(): array
    {
        return $this->payments;
    }
}
