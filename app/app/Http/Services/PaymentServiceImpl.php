<?php

namespace App\Http\Services;

use App\Http\Services\DTO\Common\MemberCategoryHistory;
use App\Http\Services\DTO\Common\MemberHistory;
use App\Http\Services\DTO\Common\Payment;
use App\Http\Services\DTO\PaymentService\GroupMember;
use App\Http\Services\DTO\PaymentService\MemberCategoryForHistory;
use App\Http\Services\DTO\PaymentService\PaymentsAndRelatedDataForEdit;
use Carbon\Carbon;

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

    public function getMemberHistoriesInGroup(int $summaryYm): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $memberHistories = $this->memberRepository->selectMemberHistoriesByGroupId($summaryYm, $authMember->getGroupId());

        return $memberHistories;
    }

    public function getMemberCategoryHistoriesInGroup(int $summaryYm): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $memberCategoryHistories = $this->memberCategoryRepository->getMemberCategoryHistoriesByYmGroup($summaryYm, $authMember->getGroupId());

        return $memberCategoryHistories;
    }

    public function getPaymentSummaryIncome(int $summaryYm): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $paymentSummaryIncome = $this->paymentRepository->selectPaymentSummaryByCategoryMember($authMember->getGroupId(), $summaryYm, true);

        return $paymentSummaryIncome;
    }

    public function getPaymentSummaryExpense(int $summaryYm): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $paymentSummaryExpense = $this->paymentRepository->selectPaymentSummaryByCategoryMember($authMember->getGroupId(), $summaryYm, false);

        return $paymentSummaryExpense;
    }

    public function getExpenseTotalByMemberLastMonth(int $summaryYm): array
    {
        $authMember = $this->memberRepository->selectMemberByAuth();
        $lastMonth = $this->getLastMonth($summaryYm);
        $expenseByMemberLastMonth = $this->paymentRepository->selectPaymentTotalByMember($authMember->getGroupId(), $lastMonth, false);

        return $expenseByMemberLastMonth;
    }

    private function getLastMonth(int $yyyymm)
    {
        $currentYear = substr($yyyymm, 0, 4);
        $currentMonth = substr($yyyymm, 4, 2);
        $lastMonthTime = strtotime($currentYear . '-' . $currentMonth . '-01 -1 month');
        return date('Ym', $lastMonthTime);
    }

    public function getOrCreateMemberAndCategoryHistoryWithPayments(int $summaryYm): PaymentsAndRelatedDataForEdit
    {
        $this->commonRepository->beginTransaction();
        $paymentsAndRelatedDataForEdit = null;
        try {
            $authMember = $this->memberRepository->selectMemberByAuth();
            $groupId = $authMember->getGroupId();
            $groupMembers = $this->memberRepository->selectGroupMember($groupId);

            $memberHistories = $this->getOrCreateMemberHistory($summaryYm, $groupId, $groupMembers);
            $memberCategoryHistories = $this->getOrCreateMemberCategoryHistory($summaryYm, $groupId, $groupMembers);

            $paymentsForEdit = $this->paymentRepository->selectPaymentsForEditByGroup($groupId, $summaryYm);

            $this->commonRepository->commit();

            $paymentsAndRelatedDataForEdit = new PaymentsAndRelatedDataForEdit($memberHistories, $memberCategoryHistories, $paymentsForEdit);

        } catch (\Exception $e) {
            $this->commonRepository->rollBack();
        }
        return $paymentsAndRelatedDataForEdit;
    }

    /**
     * @param int $summaryYm
     * @param int $groupId
     * @param array<GroupMember> $groupMembers
     * @return array<MemberHistory>
     */
    private function getOrCreateMemberHistory(int $summaryYm, int $groupId, array $groupMembers): array
    {
        $memberHistories = $this->memberRepository->selectMemberHistoriesByGroupId($summaryYm, $groupId);
        if (count($memberHistories) === 0) {
            $memberHistoriesForInsert = $this->createMemberHistories($groupMembers, $summaryYm);
            $this->memberRepository->insertMemberHistories($memberHistoriesForInsert);

            $memberHistories = $this->memberRepository->selectMemberHistoriesByGroupId($summaryYm, $groupId);
        }

        return $memberHistories;
    }

    /**
     * @param array<GroupMember> $groupMembers
     * @param string $summaryYm
     * @return array<MemberHistory>
     */
    private function createMemberHistories(array $groupMembers, string $summaryYm): array
    {
        $memberHistories = array();
        foreach ($groupMembers as $groupMember) {
            $memberHistories[] = new MemberHistory(
                0,
                $summaryYm,
                $groupMember->getGroupId(),
                $groupMember->getGroupName(),
                $groupMember->getMemberId(),
                $groupMember->getMemberName(),
                false,
                Carbon::now(),
                Carbon::now()
            );
        }
        return $memberHistories;
    }

    /**
     * @param int $summaryYm
     * @param int $groupId
     * @param array<GroupMember> $groupMembers
     * @return array<MemberCategoryHistory>
     */
    private function getOrCreateMemberCategoryHistory(int $summaryYm, int $groupId, array $groupMembers): array
    {
        $memberCategoryHistories = $this->memberCategoryRepository->selectMemberCategoryHistoriesByGroupId($summaryYm, $groupId);
        if (count($memberCategoryHistories) === 0) {
            $memberCategoriesForHistory = $this->memberCategoryRepository->selectMemberCategoriesByMembersForHistory($groupMembers);
            $memberCategoryHistoriesForInsert = $this->createMemberCategoryHistories($memberCategoriesForHistory, $summaryYm);
            $this->memberCategoryRepository->insertMemberCategoryHistories($memberCategoryHistoriesForInsert);

            $memberCategoryHistories = $this->memberCategoryRepository->selectMemberCategoryHistoriesByGroupId($summaryYm, $groupId);
        }

        return $memberCategoryHistories;
    }

    /**
     * @param array<MemberCategoryForHistory> $memberCategoriesForHistory
     * @param string $summaryYm
     * @return array<MemberCategoryHistory>
     */
    private function createMemberCategoryHistories(array $memberCategoriesForHistory, string $summaryYm): array
    {
        $memberCategoryHistoryArray = array();
        foreach ($memberCategoriesForHistory as $memberCategoryForHistory) {
            $memberCategoryHistoryArray[] = new MemberCategoryHistory(
                0,
                $summaryYm,
                $memberCategoryForHistory->getMemberId(),
                $memberCategoryForHistory->getCategoryId(),
                $memberCategoryForHistory->getCategoryName(),
                $memberCategoryForHistory->getDisplayOrder(),
                $memberCategoryForHistory->isIncomeFlg(),
                false,
                Carbon::now(),
                Carbon::now()
            );
        }

        return $memberCategoryHistoryArray;
    }

    public function updatePayment(Payment $payment): Payment
    {
        $this->commonRepository->beginTransaction();
        try {
            $payment = $this->paymentRepository->updatePayment($payment);
            $this->commonRepository->commit();
            return $payment;
        } catch (\Exception $e) {
            $this->commonRepository->rollBack();
        }
    }

    public function deleteMonthlyPayments(int $summaryYm): void
    {
        $this->commonRepository->beginTransaction();
        try {
            $authMember = $this->memberRepository->selectMemberByAuth();
            $groupId = $authMember->getGroupId();
            $groupMembers = $this->memberRepository->selectGroupMember($groupId);
            $memberIDs = $this->getMemberIDs($groupMembers);

            $this->memberCategoryRepository->deleteMemberCategoryHistory($summaryYm, $memberIDs);
            $this->memberRepository->deleteMemberHistory($summaryYm, $groupId);
            $this->paymentRepository->deletePayments($summaryYm, $groupId, $memberIDs);

            $this->commonRepository->commit();
        } catch (\Exception $e) {
            $this->commonRepository->rollBack();
        }
    }

    /**
     * @param array<GroupMember> $groupMembers
     * @return array
     */
    private function getMemberIDs(array $groupMembers)
    {
        $memberIDs = array();
        foreach ($groupMembers as $groupMember) {
            $memberIDs[] = $groupMember->getMemberId();
        }
        return $memberIDs;
    }

    public function deletePayment(Payment $payment): Payment
    {
        $this->commonRepository->beginTransaction();
        try {
            $payment = $this->paymentRepository->deletePayment($payment);
            $this->commonRepository->commit();
            return $payment;
        } catch (\Exception $e) {
            $this->commonRepository->rollBack();
        }
    }
}
