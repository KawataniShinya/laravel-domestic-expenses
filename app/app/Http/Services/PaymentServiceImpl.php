<?php

namespace App\Http\Services;

class PaymentServiceImpl implements PaymentService
{
    private MemberRepository $memberRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(MemberRepository $memberRepository, PaymentRepository $paymentRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaymentTotalMonthly(): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $payments = $this->paymentRepository->selectPaymentTotalMonthlyInGroup($authMember->getGroupId());

        return $payments;
    }
}
