<?php

namespace App\Http\Services;

class PaymentServiceImpl implements PaymentService
{
    private PaymentRepository $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaymentTotalMonthly(int $groupId)
    {
        return $this->paymentRepository->selectPaymentTotalMonthlyInGroup($groupId);
    }
}
