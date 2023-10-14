<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Services\PaymentService;
use App\Models\AuthMember;
use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\MemberCategoryHistory;
use App\Models\MemberHistory;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        $paymentArray = [];
        foreach ($payments as $payment) {
            $paymentArray[] = [
                'summary_ym' => $payment->getSummaryYm(),
                'income' => $payment->getIncome(),
                'expense' => $payment->getExpense(),
                'total' => $payment->getTotal()
            ];
        }

        return Inertia::render(
            'Payments/index',
            [
                'payments' => $paymentArray
            ]
        );
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

        $insertedPaymentArray = [
            "payment_id" => $insertedPayment->getPaymentId(),
            "summary_ym" => $insertedPayment->getSummaryYm(),
            "group_id" => $insertedPayment->getGroupId(),
            "member_id" => $insertedPayment->getMemberId(),
            "category_id" => $insertedPayment->getCategoryId(),
            "categorized_payment_id" => $insertedPayment->getCategorizedPaymentId(),
            "payment_date" => $insertedPayment->getPaymentDate(),
            "amount" => $insertedPayment->getAmount(),
            "payment_label" => $insertedPayment->getPaymentLabel(),
            "del_flg" => $insertedPayment->isDelFlg(),
            "created_at" => $insertedPayment->getCreatedAt(),
            "updated_at" => $insertedPayment->getUpdatedAt()
        ];

        $paymentsAndRelatedDataForEdit = $this->paymentService->getPaymentsAndRelatedDataForEdit($request->summary_ym, $request->group_id);

        $memberHistoryArray = [];
        foreach ($paymentsAndRelatedDataForEdit->getMemberHistories() as $memberHistory) {
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

        $memberCategoryHistoryArray = [];
        foreach ($paymentsAndRelatedDataForEdit->getMemberCategoryHistories() as $memberCategoryHistory) {
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

        $paymentArray = [];
        foreach ($paymentsAndRelatedDataForEdit->getPayments() as $paymentForEdit) {
            $paymentArray[] = [
                "payment_id" => $paymentForEdit->getPaymentId(),
                "summary_ym" => $paymentForEdit->getSummaryYm(),
                "member_id" => $paymentForEdit->getMemberId(),
                "category_id" => $paymentForEdit->getCategoryId(),
                "category_name" => $paymentForEdit->getCategoryName(),
                "categorized_payment_id" => $paymentForEdit->getCategorizedPaymentId(),
                "payment_date" => $paymentForEdit->getPaymentDate(),
                "amount" => $paymentForEdit->getAmount(),
                "payment_label" => $paymentForEdit->getPaymentLabel()
            ];
        }

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
        $authMember = AuthMember::query()->get();
        $groupId = $authMember[0]->group_id;

        $memberHistorySubQuery = MemberHistory::where('summary_ym', $summary_ym)
            ->where('group_id', $groupId);
        $memberHistory = $memberHistorySubQuery->get();

        $memberIDs = $memberHistorySubQuery->pluck('member_id')->toArray();
        $memberCategoryHistory = MemberCategoryHistory::where('summary_ym', $summary_ym)
            ->whereIn('member_id', $memberIDs)
            ->groupBy('category_id')
            ->selectRaw('category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
            ->orderBy('display_order')
            ->get();

        $paymentSummaryIncome = Payment::paymentSummaryByCategoryMember($groupId, $summary_ym, true)->get();
        $paymentSummaryExpense = Payment::paymentSummaryByCategoryMember($groupId, $summary_ym, false)->get();

        $lastMonth = $this->getLastMonth($summary_ym);
        $paymentExpenseByMemberLastMonth = Payment::paymentSummaryByMember($groupId, $lastMonth, false)->get();

        return Inertia::render(
            'Payments/summary',
            [
                'summary_ym' => $summary_ym,
                'members' => $memberHistory,
                'categories' => $memberCategoryHistory,
                'paymentsIncome' => $paymentSummaryIncome,
                'paymentsExpense' => $paymentSummaryExpense,
                'paymentExpenseByMemberLastMonth' => $paymentExpenseByMemberLastMonth
            ]
        );
    }

    private function getLastMonth(int $yyyymm)
    {
        $currentYear = substr($yyyymm, 0, 4);
        $currentMonth = substr($yyyymm, 4, 2);
        $lastMonthTime = strtotime($currentYear . '-' . $currentMonth . '-01 -1 month');
        return date('Ym', $lastMonthTime);
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
    public function editPayments(string $summary_ym)
    {
        DB::beginTransaction();

        try {
            $authMember = AuthMember::query()->get();
            $groupId = $authMember[0]->group_id;

            $memberHistories = $this->getOrCreateMemberHistory($summary_ym, $groupId);
            $memberIDs = $this->getMemberIDs($memberHistories);

            $memberCategoryHistories = $this->getOrCreateMemberCategoryHistory($summary_ym, $memberIDs);

            $payments = Payment::paymentsForGroup($groupId, $memberIDs, $summary_ym)->get();

            DB::commit();

            return Inertia::render(
                'Payments/edit',
                [
                    'summary_ym' => $summary_ym,
                    'members' => $memberHistories,
                    'memberCategories' => $memberCategoryHistories,
                    'payments' => $payments
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    private function getOrCreateMemberHistory(string $summary_ym, int $groupId)
    {
        $memberHistory = MemberHistory::where('summary_ym', $summary_ym)
            ->where('group_id', $groupId)
            ->orderBy('member_id')
            ->get();

        if($memberHistory->isEmpty()) {
            $groupMembers = Member::groupMembers($groupId)->get();
            $memberHistoryArray = $this->createMemberHistoryArray($groupMembers, $summary_ym);
            MemberHistory::insert($memberHistoryArray);

            return MemberHistory::where('summary_ym', $summary_ym)
                ->where('group_id', $groupId)
                ->orderBy('member_id')
                ->get();
        }
        else {
            return $memberHistory;
        }
    }

    private function createMemberHistoryArray(Collection $groupMembers, string $summary_ym)
    {
        $memberHistoryArray = array();
        foreach ($groupMembers as $groupMember) {
            $memberHistoryArray[] = [
                'summary_ym' => $summary_ym,
                'group_id' => $groupMember->group_id,
                'group_name' => $groupMember->group_name,
                'member_id' => $groupMember->member_id,
                'member_name' => $groupMember->member_name,
                'del_flg' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $memberHistoryArray;
    }

    private function getOrCreateMemberCategoryHistory(string $summary_ym, array $memberIDs)
    {
        $memberCategoryHistory = MemberCategoryHistory::where('summary_ym', $summary_ym)
            ->whereIn('member_id', $memberIDs)
            ->groupByRaw('member_id, category_id')
            ->selectRaw('member_id, category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
            ->orderByRaw('member_id, category_id, display_order')
            ->get();

        if($memberCategoryHistory->isEmpty()) {
            $memberCategories = MemberCategory::memberCategoriesByMembers($memberIDs)->get();
            $memberCategoryHistoryArray = $this->createMemberCategoryHistoryArray($memberCategories, $summary_ym);
            MemberCategoryHistory::insert($memberCategoryHistoryArray);

            return MemberCategoryHistory::where('summary_ym', $summary_ym)
                ->whereIn('member_id', $memberIDs)
                ->groupByRaw('member_id, category_id')
                ->selectRaw('member_id, category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
                ->orderByRaw('member_id, category_id, display_order')
                ->get();
        }
        else {
            return $memberCategoryHistory;
        }
    }

    /**
     * @param Collection $memberHistories
     * @return array
     */
    public function getMemberIDs(Collection $memberHistories): array
    {
        $memberIDs = array();
        foreach ($memberHistories as $memberHistory) {
            $memberIDs[] = $memberHistory->member_id;
        }
        return $memberIDs;
    }

    public function createMemberCategoryHistoryArray(Collection $memberCategories, string $summary_ym)
    {
        $memberCategoryHistoryArray = array();
        foreach ($memberCategories as $memberCategory) {
            $memberCategoryHistoryArray[] = [
                'summary_ym' => $summary_ym,
                'member_id' => $memberCategory->member_id,
                'category_id' => $memberCategory->category_id,
                'category_name' => $memberCategory->category_name,
                'display_order' => $memberCategory->display_order,
                'income_flg' => $memberCategory->income_flg,
                'del_flg' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        return $memberCategoryHistoryArray;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        DB::beginTransaction();

        try {
            $payment->payment_date = $request->payment_date;
            $payment->amount = $request->amount;
            $payment->payment_label =$request->payment_label;
            $payment->save();

            $memberHistories = MemberHistory::where('summary_ym', $payment->summary_ym)
                ->where('group_id', $payment->group_id)
                ->orderBy('member_id')
                ->get();
            $memberIDs = $this->getMemberIDs($memberHistories);
            $memberCategoryHistories = MemberCategoryHistory::where('summary_ym', $payment->summary_ym)
                ->whereIn('member_id', $memberIDs)
                ->groupByRaw('member_id, category_id')
                ->selectRaw('member_id, category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
                ->orderByRaw('member_id, category_id, display_order')
                ->get();
            $payments = Payment::paymentsForGroup($payment->group_id, $memberIDs, $payment->summary_ym)->get();

            DB::commit();

            return Inertia::render(
                'Payments/edit',
                [
                    'summary_ym' => (string)$payment->summary_ym,
                    'members' => $memberHistories,
                    'memberCategories' => $memberCategoryHistories,
                    'payments' => $payments,
                    'updatedPayment' => $payment,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function destroyRelatedPayments(string $summary_ym)
    {
        DB::beginTransaction();

        try {
            $authMember = AuthMember::query()->get();
            $groupId = $authMember[0]->group_id;

            $memberHistories = MemberHistory::where('summary_ym', $summary_ym)
                ->where('group_id', $groupId)
                ->orderBy('member_id')
                ->get();
            $memberIDs = $this->getMemberIDs($memberHistories);

            MemberCategoryHistory::where('summary_ym', $summary_ym)
                ->whereIn('member_id', $memberIDs)
                ->delete();

            MemberHistory::where('summary_ym', $summary_ym)
                ->where('group_id', $groupId)
                ->delete();

            $payments = Payment::where('summary_ym', $summary_ym)
                ->where('group_id', $groupId)
                ->whereIn('member_id', $memberIDs)
                ->get();
            foreach ($payments as $payment) {
                $payment['del_flg'] = true;
                $payment->save();
            }

            DB::commit();

            return redirect()->route('payments.index');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
