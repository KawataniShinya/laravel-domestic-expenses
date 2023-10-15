<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\Payment;
use App\Http\Services\DTO\PaymentService\PaymentsAndRelatedDataForEdit;
use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;

interface PaymentService
{
    /**
     * @return array<PaymentTotalMonthly>
     */
    public function getPaymentTotalMonthly(): array;

    public function storePayment(int $summaryYm, int $groupId, int $memberId, int $categoryId, string|null $paymentDate, int $amount, string|null $paymentLabel): Payment;

    public function getPaymentsAndRelatedDataForEdit(int $summaryYm, int $groupId): PaymentsAndRelatedDataForEdit;
}
