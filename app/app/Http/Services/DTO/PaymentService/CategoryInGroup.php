<?php

namespace App\Http\Services\DTO\PaymentService;

class CategoryInGroup
{
    private int $category_id;
    private string $category_name;
    private int $display_order;
    private bool $income_flg;

    public function __construct(
        int $category_id,
        string $category_name,
        int $display_order,
        bool $income_flg
    ) {
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->display_order = $display_order;
        $this->income_flg = $income_flg;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->display_order;
    }

    /**
     * @return bool
     */
    public function isIncomeFlg(): bool
    {
        return $this->income_flg;
    }
}
