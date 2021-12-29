<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Operations\Operation;

class BusinessUser extends User
{
    public function __construct(int $id, float $weeklyWithdrawFreeOfChargeAmount = 0.00, int $weeklyWithdrawFreeOfChargeOperations = 0)
    {
        parent::__construct($id, 0.03, 0.5, $weeklyWithdrawFreeOfChargeAmount, $weeklyWithdrawFreeOfChargeOperations);
    }

    public function getWithdrawCommissionFee(Operation $operation): float
    {
        return $this->withdrawCommissionFee;
    }

    public function getFeeableAmount(float $amount): float
    {
        return $amount;
    }
}
