<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\AuthMember;
use App\Models\MemberCategoryHistory;
use App\Models\MemberHistory;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
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

        $payments = Payment::where('group_id', $groupId)
            ->where('del_flg', false)
            ->groupBy('summary_ym')
            ->selectRaw('
                    summary_ym,
                    sum(case when income_flg=1 then amount else 0 end) as income,
                    sum(case when income_flg=0 then amount else 0 end) as expense,
                    sum(case when income_flg=1 then amount else amount * (-1) end) as total
                ')
            ->orderBy('summary_ym', 'desc')
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
     * @return \Illuminate\Http\Response
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
            ->selectRaw('category_id, max(category_name)')
            ->get();

        $paymentSummaryDetails = Payment::where('group_id', $groupId)
            ->where('summary_ym', $summary_ym)
            ->where('del_flg', false)
            ->groupBy('member_id', 'category_id')
            ->selectRaw('
                    member_id,
                    category_id,
                    sum(amount) as amount
                ')
            ->get();

        return to_route('dashboard');
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
