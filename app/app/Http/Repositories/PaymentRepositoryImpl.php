<?php

namespace App\Http\Repositories;

use App\Models\Payment;

class PaymentRepositoryImpl implements \App\Http\Services\PaymentRepository
{
    public function selectPaymentTotalMonthlyInGroup(int $groupId)
    {
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
        return $payments;
    }
}
