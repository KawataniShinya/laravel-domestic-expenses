<?php

namespace App\Http\Services\DTO\PaymentService;

class PaymentTotalMonthly
{
    private int $summary_ym;
    private int $income;
    private int $expense;
    private int $total;

    public function __construct(
        int $summary_ym,
        int $income,
        int $expense,
        int $total
    )
    {
        $this->summary_ym = $summary_ym;
        $this->income = $income;
        $this->expense = $expense;
        $this->total = $total;
    }

    public function getSummaryYm()
    {
        return $this->summary_ym;
    }

    public function getIncome()
    {
        return $this->income;
    }

    public function getExpense()
    {
        return $this->expense;
    }

    public function getTotal()
    {
        return $this->total;
    }
}
