<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\AuthMember;
use App\Models\MemberCategoryHistory;
use App\Models\MemberHistory;
use App\Models\Payment;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $authMember = AuthMember::query()->get();
        $groupId = $authMember[0]->group_id;

        $payments = Payment::leftJoin('member_category_histories', function ($join) {
                $join
                    ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                    ->on('payments.category_id', '=', 'member_category_histories.category_id')
                    ->on('payments.member_id', '=', 'member_category_histories.member_id');
            })
            ->where('payments.group_id', $groupId)
            ->where('payments.del_flg', false)
            ->groupBy('payments.summary_ym')
            ->selectRaw('
                    payments.summary_ym,
                    sum(case when member_category_histories.income_flg=1 then payments.amount else 0 end) as income,
                    sum(case when member_category_histories.income_flg=0 then payments.amount else 0 end) as expense,
                    sum(case when member_category_histories.income_flg=1 then payments.amount else payments.amount * (-1) end) as total
                ')
            ->orderBy('payments.summary_ym', 'desc')
            ->get();

        return Inertia::render(
            'Payments/index',
            [
                'payments' => $payments
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
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $summary_ym
     * @return \Inertia\Response
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
            ->whereIn('member_id', $memberIDs)->groupBy('category_id')
            ->selectRaw('category_id, max(category_name) as category_name, max(income_flg) as income_flg')
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
     * @return \Illuminate\Http\Response
     */
    public function editPayments(string $summary_ym)
    {
        return to_route('dashboard');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
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
}
