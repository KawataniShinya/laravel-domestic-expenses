<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Services\DTO\Common\PaymentSummary;
use App\Http\Services\DTO\PaymentService\CategoryInGroup;
use App\Http\Services\DTO\PaymentService\PaymentForEdit;
use App\Http\Services\DTO\PaymentService\PaymentTotalByMember;
use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;
use App\Http\Services\PaymentService;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $payments = $this->paymentService->getPaymentTotalMonthly();
        $paymentArray = $this->getArrayPaymentForIndex($payments);

        return Inertia::render(
            'Payments/index',
            [
                'payments' => $paymentArray
            ]
        );
    }

    /**
     * @param array<PaymentTotalMonthly> $payments
     * @return array
     */
    private function getArrayPaymentForIndex(array $payments): array
    {
        $paymentArray = [];
        foreach ($payments as $payment) {
            $paymentArray[] = [
                'summary_ym' => $payment->getSummaryYm(),
                'income' => $payment->getIncome(),
                'expense' => $payment->getExpense(),
                'total' => $payment->getTotal()
            ];
        }
        return $paymentArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentRequest  $request
     * @return Response
     */
    public function store(StorePaymentRequest $request)
    {
        $insertedPayment = $this->paymentService->storePayment(
            $request->summary_ym,
            $request->group_id,
            $request->member_id,
            $request->category_id,
            $request->payment_date,
            $request->amount,
            $request->payment_label
        );
        $insertedPaymentArray = $this->getArrayPayment($insertedPayment);

        $paymentsAndRelatedDataForEdit = $this->paymentService->getPaymentsAndRelatedDataForEdit($request->summary_ym, $request->group_id);
        $memberHistoryArray = $this->getArrayMemberHistories($paymentsAndRelatedDataForEdit->getMemberHistories());
        $memberCategoryHistoryArray = $this->getArrayMemberCategoryHistories($paymentsAndRelatedDataForEdit->getMemberCategoryHistories());
        $paymentArray = $this->getArrayPaymentsForEdit($paymentsAndRelatedDataForEdit->getPayments());

        return Inertia::render(
            'Payments/edit',
            [
                'summary_ym' => (string)$request->summary_ym,
                'updatedPayment' => $insertedPaymentArray,
                'members' => $memberHistoryArray,
                'memberCategories' => $memberCategoryHistoryArray,
                'payments' => $paymentArray,
            ]
        );
    }

    private function getArrayPayment(\App\Http\Services\DTO\Common\Payment $payment): array
    {
        $paymentArray = [
            "payment_id" => $payment->getPaymentId(),
            "summary_ym" => $payment->getSummaryYm(),
            "group_id" => $payment->getGroupId(),
            "member_id" => $payment->getMemberId(),
            "category_id" => $payment->getCategoryId(),
            "categorized_payment_id" => $payment->getCategorizedPaymentId(),
            "payment_date" => $payment->getPaymentDate(),
            "amount" => $payment->getAmount(),
            "payment_label" => $payment->getPaymentLabel(),
            "del_flg" => $payment->isDelFlg(),
            "created_at" => $payment->getCreatedAt(),
            "updated_at" => $payment->getUpdatedAt()
        ];

        return $paymentArray;
    }

    /**
     * @param array<\App\Http\Services\DTO\Common\MemberHistory> $memberHistories
     * @return array
     */
    private function getArrayMemberHistories(array $memberHistories): array
    {
        $memberHistoryArray = [];
        foreach ($memberHistories as $memberHistory) {
            $memberHistoryArray[] = [
                "member_history_id" => $memberHistory->getMemberHistoryId(),
                "summary_ym" => $memberHistory->getSummaryYm(),
                "group_id" => $memberHistory->getGroupId(),
                "group_name" => $memberHistory->getGroupName(),
                "member_id" => $memberHistory->getMemberId(),
                "member_name" => $memberHistory->getMemberName(),
                "del_flg" => $memberHistory->isDelFlg(),
                "created_at" => $memberHistory->getCreatedAt(),
                "updated_at" => $memberHistory->getUpdatedAt()
            ];
        }

        return $memberHistoryArray;
    }

    /**
     * @param array<\App\Http\Services\DTO\Common\MemberCategoryHistory> $memberCategoryHistories
     * @return array
     */
    private function getArrayMemberCategoryHistories(array $memberCategoryHistories): array
    {
        $memberCategoryHistoryArray = [];
        foreach ($memberCategoryHistories as $memberCategoryHistory) {
            $memberCategoryHistoryArray[] = [
                "member_category_history_id" => $memberCategoryHistory->getMemberCategoryHistoryId(),
                "summary_ym" => $memberCategoryHistory->getSummaryYm(),
                "member_id" => $memberCategoryHistory->getMemberId(),
                "category_id" => $memberCategoryHistory->getCategoryId(),
                "category_name" => $memberCategoryHistory->getCategoryName(),
                "display_order" => $memberCategoryHistory->getDisplayOrder(),
                "income_flg" => $memberCategoryHistory->isIncomeFlg(),
                "del_flg" => $memberCategoryHistory->isDelFlg(),
                "created_at" => $memberCategoryHistory->getCreatedAt(),
                "updated_at" => $memberCategoryHistory->getUpdatedAt()
            ];
        }

        return $memberCategoryHistoryArray;
    }

    /**
     * @param array<PaymentForEdit> $payments
     * @return array
     */
    private function getArrayPaymentsForEdit(array $payments): array
    {
        $paymentArray = [];
        foreach ($payments as $paymentForEdit) {
            $paymentArray[] = [
                "payment_id" => $paymentForEdit->getPaymentId(),
                "summary_ym" => $paymentForEdit->getSummaryYm(),
                "member_id" => $paymentForEdit->getMemberId(),
                "category_id" => $paymentForEdit->getCategoryId(),
                "categorized_payment_id" => $paymentForEdit->getCategorizedPaymentId(),
                "payment_date" => $paymentForEdit->getPaymentDate(),
                "amount" => $paymentForEdit->getAmount(),
                "payment_label" => $paymentForEdit->getPaymentLabel()
            ];
        }

        return $paymentArray;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Payment $payment)
    {
        return redirect()->route('payments.editPayments', ['summary_ym' => $payment->summary_ym]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $summary_ym
     * @return Response
     */
    public function showSummary(string $summary_ym)
    {
        $memberHistory = $this->getArrayMemberHistories(
            $this->paymentService->getMemberHistoriesInGroup($summary_ym)
        );

        $memberCategoryHistory = $this->getArrayCategoriesInGroup(
            $this->paymentService->getMemberCategoryHistoriesInGroup($summary_ym)
        );

        $paymentSummaryIncome = $this->getArrayPaymentSummary(
            $this->paymentService->getPaymentSummaryIncome($summary_ym)
        );

        $paymentSummaryExpense = $this->getArrayPaymentSummary(
            $this->paymentService->getPaymentSummaryExpense($summary_ym)
        );

        $expenseByMemberLastMonth = $this->getArrayPaymentTotalByMember(
            $this->paymentService->getExpenseTotalByMemberLastMonth($summary_ym)
        );

        return Inertia::render(
            'Payments/summary',
            [
                'summary_ym' => $summary_ym,
                'members' => $memberHistory,
                'categories' => $memberCategoryHistory,
                'paymentsIncome' => $paymentSummaryIncome,
                'paymentsExpense' => $paymentSummaryExpense,
                'paymentExpenseByMemberLastMonth' => $expenseByMemberLastMonth
            ]
        );
    }

    /**
     * @param array<CategoryInGroup> $categoriesInGroup
     * @return array
     */
    private function getArrayCategoriesInGroup(array $categoriesInGroup): array
    {
        $categoryInGroupArray = [];
        foreach ($categoriesInGroup as $categoryInGroup) {
            $categoryInGroupArray[] = [
                "category_id" => $categoryInGroup->getCategoryId(),
                "category_name" => $categoryInGroup->getCategoryName(),
                "display_order" => $categoryInGroup->getDisplayOrder(),
                "income_flg" => $categoryInGroup->isIncomeFlg() === true ? 1 : 0
            ];
        }

        return $categoryInGroupArray;
    }

    /**
     * @param array<PaymentSummary> $paymentsSummary
     * @return array
     */
    private function getArrayPaymentSummary(array $paymentsSummary): array
    {
        $paymentSummaryArray = [];
        foreach ($paymentsSummary as $paymentSummary) {
            $paymentSummaryArray[] = [
                "member_id" => $paymentSummary->getMemberId(),
                "category_id" => $paymentSummary->getCategoryId(),
                "amount" => $paymentSummary->getAmount()
            ];
        }

        return $paymentSummaryArray;
    }

    /**
     * @param array<PaymentTotalByMember> $paymentTotalByMember
     * @return array
     */
    private function getArrayPaymentTotalByMember(array $paymentTotalByMember): array
    {
        $paymentTotalByMemberArray = [];
        foreach ($paymentTotalByMember as $totals) {
            $paymentTotalByMemberArray[] = [
                "member_id" => $totals->getMemberId(),
                "amount" => $totals->getAmount()
            ];
        }

        return $paymentTotalByMemberArray;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $summary_ym
     * @return Response
     */
    public function editPayments(string $summary_ym): Response
    {
        $paymentsAndRelatedDataForEdit = $this->paymentService->getOrCreateMemberAndCategoryHistoryWithPayments($summary_ym);
        $memberHistoryArray = $this->getArrayMemberHistories($paymentsAndRelatedDataForEdit->getMemberHistories());
        $memberCategoryHistoryArray = $this->getArrayMemberCategoryHistories($paymentsAndRelatedDataForEdit->getMemberCategoryHistories());
        $paymentArray = $this->getArrayPaymentsForEdit($paymentsAndRelatedDataForEdit->getPayments());

        return Inertia::render(
            'Payments/edit',
            [
                'summary_ym' => $summary_ym,
                'members' => $memberHistoryArray,
                'memberCategories' => $memberCategoryHistoryArray,
                'payments' => $paymentArray
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): Response
    {
        $payment->payment_date = $request->payment_date;
        $payment->amount = $request->amount;
        $payment->payment_label = $request->payment_label;
        $paymentDTO = $this->paymentService->updatePayment($this->setPaymentFromModelToDTO($payment));
        $updatedPaymentArray = $this->getArrayPayment($paymentDTO);

        $paymentsAndRelatedDataForEdit = $this->paymentService->getPaymentsAndRelatedDataForEdit($paymentDTO->getSummaryYm(), $paymentDTO->getGroupId());
        $memberHistoryArray = $this->getArrayMemberHistories($paymentsAndRelatedDataForEdit->getMemberHistories());
        $memberCategoryHistoryArray = $this->getArrayMemberCategoryHistories($paymentsAndRelatedDataForEdit->getMemberCategoryHistories());
        $paymentArray = $this->getArrayPaymentsForEdit($paymentsAndRelatedDataForEdit->getPayments());

        return Inertia::render(
            'Payments/edit',
            [
                'summary_ym' => (string)$payment->summary_ym,
                'members' => $memberHistoryArray,
                'memberCategories' => $memberCategoryHistoryArray,
                'payments' => $paymentArray,
                'updatedPayment' => $updatedPaymentArray,
            ]
        );
    }

    private function setPaymentFromModelToDTO(Payment $paymentModel): \App\Http\Services\DTO\Common\Payment
    {
        $paymentDTO = new \App\Http\Services\DTO\Common\Payment(
            $paymentModel->payment_id,
            $paymentModel->summary_ym,
            $paymentModel->group_id,
            $paymentModel->member_id,
            $paymentModel->category_id,
            $paymentModel->categorized_payment_id,
            $paymentModel->payment_date,
            $paymentModel->amount,
            $paymentModel->payment_label,
            $paymentModel->del_flg,
            $paymentModel->created_at,
            $paymentModel->updated_at
        );
        return $paymentDTO;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $paymentDTO = $this->paymentService->deletePayment($this->setPaymentFromModelToDTO($payment));
        $deletedPaymentArray = $this->getArrayPayment($paymentDTO);

        $paymentsAndRelatedDataForEdit = $this->paymentService->getPaymentsAndRelatedDataForEdit($paymentDTO->getSummaryYm(), $paymentDTO->getGroupId());
        $memberHistoryArray = $this->getArrayMemberHistories($paymentsAndRelatedDataForEdit->getMemberHistories());
        $memberCategoryHistoryArray = $this->getArrayMemberCategoryHistories($paymentsAndRelatedDataForEdit->getMemberCategoryHistories());
        $paymentArray = $this->getArrayPaymentsForEdit($paymentsAndRelatedDataForEdit->getPayments());

        return Inertia::render(
            'Payments/edit',
            [
                'summary_ym' => (string)$payment->summary_ym,
                'members' => $memberHistoryArray,
                'memberCategories' => $memberCategoryHistoryArray,
                'payments' => $paymentArray,
                'updatedPayment' => $deletedPaymentArray,
            ]
        );
    }

    public function destroyRelatedPayments(string $summary_ym): \Illuminate\Http\RedirectResponse
    {
        $this->paymentService->deleteMonthlyPayments($summary_ym);
        return redirect()->route('payments.index');
    }
}
