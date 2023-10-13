<?php

namespace App\Http\Services;

use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;

interface PaymentRepository
{
    /**
     * @param int $groupId
     * @return array<PaymentTotalMonthly>
     */
    public function selectPaymentTotalMonthlyInGroup(int $groupId): array;
}
