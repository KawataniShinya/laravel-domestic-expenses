<?php

namespace App\Http\Services\DTO\PaymentService;

class PaymentForEdit
{
    private int $payment_id;
    private int|null $summary_ym;
    private int|null $member_id;
    private int|null $category_id;
    private string|null $category_name;
    private int|null $categorized_payment_id;
    private string|null $payment_date;
    private int|null $amount;
    private string|null $payment_label;

    public function __construct(
        int $payment_id,
        int|null $summary_ym,
        int|null $member_id,
        int|null $category_id,
        string|null $category_name,
        int|null $categorized_payment_id,
        string|null $payment_date,
        int|null $amount,
        string|null $payment_label
    )
    {
        $this->payment_id = $payment_id;
        $this->summary_ym = $summary_ym;
        $this->member_id = $member_id;
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->categorized_payment_id = $categorized_payment_id;
        $this->payment_date = $payment_date;
        $this->amount = $amount;
        $this->payment_label = $payment_label;
    }

    /**
     * @return int
     */
    public function getPaymentId(): int
    {
        return $this->payment_id;
    }

    /**
     * @return int|null
     */
    public function getSummaryYm(): ?int
    {
        return $this->summary_ym;
    }

    /**
     * @return int|null
     */
    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    /**
     * @return string|null
     */
    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    /**
     * @return int|null
     */
    public function getCategorizedPaymentId(): ?int
    {
        return $this->categorized_payment_id;
    }

    /**
     * @return string|null
     */
    public function getPaymentDate(): ?string
    {
        return $this->payment_date;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getPaymentLabel(): ?string
    {
        return $this->payment_label;
    }
}
