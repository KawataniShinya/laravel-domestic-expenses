<?php

namespace App\Http\Services;

use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;

interface PaymentService
{
    /**
     * @return array<PaymentTotalMonthly>
     */
    public function getPaymentTotalMonthly(): array;
}
