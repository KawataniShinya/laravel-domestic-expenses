<?php

namespace App\Http\Repositories;

use App\Http\Services\DTO\Common\PaymentSummary;
use App\Http\Services\DTO\PaymentService\PaymentForEdit;
use App\Http\Services\DTO\PaymentService\PaymentTotalByMember;
use App\Http\Services\DTO\PaymentService\PaymentTotalMonthly;
use App\Http\Services\PaymentRepository;
use App\Models\Payment;

class PaymentRepositoryImpl implements PaymentRepository
{
    public function selectPaymentTotalMonthlyInGroup(int $group_id): array
    {
        $payments = Payment::leftJoin('member_category_histories', function ($join) {
                $join
                    ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                    ->on('payments.category_id', '=', 'member_category_histories.category_id')
                    ->on('payments.member_id', '=', 'member_category_histories.member_id');
            })
            ->where('payments.group_id', $group_id)
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

        $paymentsDTOArray = [];
        foreach ($payments as $payment) {
            $paymentsDTOArray[] = new PaymentTotalMonthly(
                $payment->summary_ym,
                $payment->income,
                $payment->expense,
                $payment->total
            );
        }

        return $paymentsDTOArray;
    }

    public function selectMaxCategorizedPaymentId(int $summary_ym, int $group_id, int $member_id, int $category_id): int
    {
        $maxCategorizedPaymentId = Payment::where('summary_ym', $summary_ym)
            ->where('group_id', $group_id)
            ->where('member_id', $member_id)
            ->where('category_id', $category_id)
            ->selectRaw('max(categorized_payment_id) as categorized_payment_id')
            ->first();

        if (is_null($maxCategorizedPaymentId->categorized_payment_id)) {
            return 0;
        }
        else {
            return $maxCategorizedPaymentId->categorized_payment_id;
        }
    }

    public function insertPayment(int $summary_ym, int $group_id, int $member_id, int $category_id, int|null $categorized_payment_id, string|null $payment_date, int $amount, string|null $payment_label): \App\Http\Services\DTO\Common\Payment
    {
        $payment = Payment::create([
            'summary_ym' => $summary_ym,
            'group_id' => $group_id,
            'member_id' => $member_id,
            'category_id' => $category_id,
            'categorized_payment_id' => $categorized_payment_id,
            'payment_date' => $payment_date,
            'amount' => $amount,
            'payment_label' => $payment_label,
            'del_flg' => false
        ]);

        return new \App\Http\Services\DTO\Common\Payment(
            $payment->payment_id,
            $payment->summary_ym,
            $payment->group_id,
            $payment->member_id,
            $payment->category_id,
            $payment->categorized_payment_id,
            $payment->payment_date,
            $payment->amount,
            $payment->payment_label,
            $payment->del_flg,
            $payment->created_at,
            $payment->updated_at
        );
    }

    public function selectPaymentsForEditByGroup(int $group_id, string $summary_ym): array
    {
        $paymentsForEdit = Payment::leftJoin('member_category_histories', function ($join) {
                $join
                    ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                    ->on('payments.category_id', '=', 'member_category_histories.category_id')
                    ->on('payments.member_id', '=', 'member_category_histories.member_id');
            })
            ->where('payments.group_id', $group_id)
            ->where('payments.summary_ym', $summary_ym)
            ->where('payments.del_flg', false)
            ->selectRaw('payments.payment_id, payments.summary_ym, payments.member_id, payments.category_id, member_category_histories.category_name, payments.categorized_payment_id, payments.payment_date, payments.amount, payments.payment_label')
            ->orderByRaw('payments.member_id, payments.category_id, payments.categorized_payment_id')
            ->get();

        $paymentForEditArray = [];
        foreach ($paymentsForEdit as $payment) {
            $paymentForEditArray[] = new PaymentForEdit(
                $payment->payment_id,
                $payment->summary_ym,
                $payment->member_id,
                $payment->category_id,
                $payment->category_name,
                $payment->categorized_payment_id,
                $payment->payment_date,
                $payment->amount,
                $payment->payment_label
            );
        }

        return $paymentForEditArray;
    }

    public function selectPaymentSummaryByCategoryMember(int $group_id, string $summary_ym, bool $income_flg): array
    {
        $paymentsSummary = Payment::leftJoin('member_category_histories', function ($join) {
                $join
                    ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                    ->on('payments.category_id', '=', 'member_category_histories.category_id')
                    ->on('payments.member_id', '=', 'member_category_histories.member_id');
            })
            ->where('payments.group_id', $group_id)
            ->where('payments.summary_ym', $summary_ym)
            ->where('member_category_histories.income_flg', $income_flg)
            ->where('payments.del_flg', false)
            ->groupBy('payments.member_id', 'payments.category_id')
            ->selectRaw('
                    payments.member_id as member_id,
                    payments.category_id as category_id,
                    sum(payments.amount) as amount
                ')
            ->orderBy('member_id')
            ->get();

        $paymentSummaryArray = [];
        foreach ($paymentsSummary as $paymentSummary) {
            $paymentSummaryArray[] = new PaymentSummary(
                $paymentSummary->member_id,
                $paymentSummary->category_id,
                $paymentSummary->amount
            );
        }

        return $paymentSummaryArray;
    }

    public function selectPaymentTotalByMember(int $group_id, string $summary_ym, bool $income_flg): array
    {
        $paymentTotalByMember = Payment::leftJoin('member_category_histories', function ($join) {
            $join
                ->on('payments.summary_ym', '=', 'member_category_histories.summary_ym')
                ->on('payments.category_id', '=', 'member_category_histories.category_id')
                ->on('payments.member_id', '=', 'member_category_histories.member_id');
        })
            ->where('payments.group_id', $group_id)
            ->where('payments.summary_ym', $summary_ym)
            ->where('member_category_histories.income_flg', $income_flg)
            ->where('payments.del_flg', false)
            ->groupBy('payments.member_id')
            ->selectRaw('
                    payments.member_id as member_id,
                    sum(payments.amount) as amount
                ')
            ->orderBy('member_id')
            ->get();

        $paymentTotalByMemberArray = [];
        foreach ($paymentTotalByMember as $paymentTotal) {
            $paymentTotalByMemberArray[] = new PaymentTotalByMember(
                $paymentTotal->member_id,
                $paymentTotal->amount
            );
        }

        return $paymentTotalByMemberArray;
    }
}
