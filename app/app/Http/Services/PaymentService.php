<?php

namespace App\Http\Services;

interface PaymentService
{
    public function getPaymentTotalMonthly(int $groupId);
}
