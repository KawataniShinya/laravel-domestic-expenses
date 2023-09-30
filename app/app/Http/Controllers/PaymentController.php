<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\AuthMember;
use App\Models\Category;
use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\MemberCategoryHistory;
use App\Models\MemberHistory;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use function PHPUnit\Framework\isEmpty;

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
     * @return \Illuminate\Http\Response
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

            return to_route('dashboard');
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
            ->groupBy('category_id')
            ->selectRaw('category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
            ->orderBy('display_order')
            ->get();

        if($memberCategoryHistory->isEmpty()) {
            $memberCategories = MemberCategory::memberCategoriesByMembers($memberIDs)->get();
            $memberCategoryHistoryArray = $this->createMemberCategoryHistoryArray($memberCategories, $summary_ym);
            MemberCategoryHistory::insert($memberCategoryHistoryArray);

            return MemberCategoryHistory::where('summary_ym', $summary_ym)
                ->whereIn('member_id', $memberIDs)
                ->groupBy('category_id')
                ->selectRaw('category_id, max(category_name) as category_name, max(display_order) as display_order, max(income_flg) as income_flg')
                ->orderBy('display_order')
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
