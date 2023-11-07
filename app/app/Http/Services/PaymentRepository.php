<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\Payment;
use App\Http\Services\DTO\Common\PaymentSummary;
use App\Http\Services\DTO\PaymentService\PaymentForEdit;
use App\Http\Services\DTO\PaymentService\PaymentTotalByMember;
use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;

interface PaymentRepository
{
    /**
     * @param int $group_id
     * @return array<PaymentTotalMonthly>
     */
    public function selectPaymentTotalMonthlyInGroup(int $group_id): array;

    /**
     * @param int $summary_ym
     * @param int $group_id
     * @param int $member_id
     * @param int $category_id
     * @return int
     */
    public function selectMaxCategorizedPaymentId(int $summary_ym, int $group_id, int $member_id, int $category_id): int;

    public function insertPayment(int $summary_ym, int $group_id, int $member_id, int $category_id, int|null $categorized_payment_id, string|null $payment_date, int $amount, string|null $payment_label): Payment;

    /**
     * @param int $group_id
     * @param string $summary_ym
     * @return array<PaymentForEdit>
     */
    public function selectPaymentsForEditByGroup(int $group_id, string $summary_ym): array;

    /**
     * @param int $group_id
     * @param string $summary_ym
     * @param bool $income_flg
     * @return array<PaymentSummary>
     */
    public function selectPaymentSummaryByCategoryMember(int $group_id, string $summary_ym, bool $income_flg): array;

    /**
     * @param int $group_id
     * @param string $summary_ym
     * @param bool $income_flg
     * @return array<PaymentTotalByMember>
     */
    public function selectPaymentTotalByMember(int $group_id, string $summary_ym, bool $income_flg): array;
}
