<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\Payment;
use App\Http\Services\DTO\PaymentService\PaymentsAndRelatedDataForEdit;

class PaymentServiceImpl implements PaymentService
{
    private CommonRepository $commonRepository;
    private MemberRepository $memberRepository;
    private MemberCategoryRepository $memberCategoryRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(
        CommonRepository $commonRepository,
        MemberRepository $memberRepository,
        MemberCategoryRepository $memberCategoryRepository,
        PaymentRepository $paymentRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->memberRepository = $memberRepository;
        $this->memberCategoryRepository = $memberCategoryRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaymentTotalMonthly(): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $payments = $this->paymentRepository->selectPaymentTotalMonthlyInGroup($authMember->getGroupId());

        return $payments;
    }

    public function storePayment(int $summaryYm, int $groupId, int $memberId, int $categoryId, string|null $paymentDate, int $amount, string|null $paymentLabel): Payment
    {
        $maxCategorizedPaymentId = $this->paymentRepository->selectMaxCategorizedPaymentId($summaryYm, $groupId, $memberId, $categoryId);
        $nextCategorizedPaymentId = $maxCategorizedPaymentId === 0 ? 1 : $maxCategorizedPaymentId + 1;
        $this->commonRepository->beginTransaction();
        try {
            $payment = $this->paymentRepository->insertPayment($summaryYm, $groupId, $memberId, $categoryId, $nextCategorizedPaymentId, $paymentDate, $amount, $paymentLabel);
            $this->commonRepository->commit();
            return $payment;
        } catch (\Exception $e) {
            $this->commonRepository->rollBack();
        }
    }

    public function getPaymentsAndRelatedDataForEdit(int $summaryYm, int $groupId): PaymentsAndRelatedDataForEdit
    {
        $memberHistories = $this->memberRepository->selectMemberHistoriesByGroupId($summaryYm, $groupId);
        $memberIDs = [];
        foreach ($memberHistories as $memberHistory) {
            $memberIDs[] = $memberHistory->getMemberId();
        }
        $memberCategoryHistories = $this->memberCategoryRepository->getMemberCategoryHistoriesByYmMembers($summaryYm, $memberIDs);
        $paymentsForEdit = $this->paymentRepository->selectPaymentsForEditByGroup($groupId, $summaryYm);

        return new PaymentsAndRelatedDataForEdit($memberHistories, $memberCategoryHistories, $paymentsForEdit);
    }
}
