<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Operations;

class DepositOperation extends Operation
{
    public function getCommissionFeeAmount(): float
    {
        $fee = $this->getUser()->getDepositCommissionFee();

        $commissionFeeAmount = $this->amount * $fee;

        $commissionFeeAmount = ceil($commissionFeeAmount);

        return $commissionFeeAmount / 100;
    }
}
