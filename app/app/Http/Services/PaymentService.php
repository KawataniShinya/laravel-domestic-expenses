<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberHistory;
use App\Http\Services\DTO\Common\Payment;
use App\Http\Services\DTO\Common\PaymentSummary;
use App\Http\Services\DTO\PaymentService\CategoryInGroup;
use App\Http\Services\DTO\PaymentService\PaymentsAndRelatedDataForEdit;
use App\Http\Services\DTO\PaymentService\PaymentTotalByMember;
use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;

interface PaymentService
{
    /**
     * @return array<PaymentTotalMonthly>
     */
    public function getPaymentTotalMonthly(): array;

    public function storePayment(int $summaryYm, int $groupId, int $memberId, int $categoryId, string|null $paymentDate, int $amount, string|null $paymentLabel): Payment;

    public function getPaymentsAndRelatedDataForEdit(int $summaryYm, int $groupId): PaymentsAndRelatedDataForEdit;

    /**
     * @param int $summaryYm
     * @return array<MemberHistory>
     */
    public function getMemberHistoriesInGroup(int $summaryYm): array;

    /**
     * @param int $summaryYm
     * @return array<CategoryInGroup>
     */
    public function getMemberCategoryHistoriesInGroup(int $summaryYm): array;

    /**
     * @param int $summaryYm
     * @return array<PaymentSummary>
     */
    public function getPaymentSummaryIncome(int $summaryYm): array;

    /**
     * @param int $summaryYm
     * @return array<PaymentSummary>
     */
    public function getPaymentSummaryExpense(int $summaryYm): array;

    /**
     * @param int $summaryYm
     * @param int $backMonths
     * @return array<PaymentTotalByMember>
     */
    public function getExpenseTotalByMemberPastMonth(int $summaryYm, int $backMonths): array;

    public function getOrCreateMemberAndCategoryHistoryWithPayments(int $summaryYm): PaymentsAndRelatedDataForEdit;

    public function updatePayment(Payment $payment): Payment;

    public function deleteMonthlyPayments(int $summaryYm): void;

    public function deletePayment(Payment $payment): Payment;
}
