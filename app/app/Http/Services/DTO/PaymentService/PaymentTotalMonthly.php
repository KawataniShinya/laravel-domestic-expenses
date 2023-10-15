<?php

namespace App\Http\Services\DTO\PaymentService;

class PaymentTotalMonthly
{
    private int $summary_ym;
    private int|null $income;
    private int|null $expense;
    private int|null $total;

    public function __construct(
        int $summary_ym,
        int|null $income,
        int|null $expense,
        int|null $total
    )
    {
        $this->summary_ym = $summary_ym;
        $this->income = $income;
        $this->expense = $expense;
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getSummaryYm(): int
    {
        return $this->summary_ym;
    }

    /**
     * @return int|null
     */
    public function getIncome(): ?int
    {
        return $this->income;
    }

    /**
     * @return int|null
     */
    public function getExpense(): ?int
    {
        return $this->expense;
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }
}
