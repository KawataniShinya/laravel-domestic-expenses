<?php

namespace App\Http\Services;

interface PaymentRepository
{
    public function selectPaymentTotalMonthlyInGroup(int $groupId);
}
