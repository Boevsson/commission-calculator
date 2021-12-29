<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Operations\Operation;
use Boevsson\CommissionTask\Service\DateService;

class PrivateUser extends User
{
    public function __construct(int $id, float $weeklyWithdrawFreeOfChargeAmount = 1000, int $weeklyWithdrawFreeOfChargeOperations = 3)
    {
        parent::__construct($id, 0.03, 0.3, $weeklyWithdrawFreeOfChargeAmount, $weeklyWithdrawFreeOfChargeOperations);
    }

    public function getWithdrawCommissionFee(Operation $operation): float
    {
        // Convert operation amount to Euro (If it already is in Euro then amount will stay the same...)
        $operationAmountInEuro = $operation->getAmount() / $operation->getCurrency()->getRate();

        if ($this->lastOperationDate && DateService::checkIfDatesAreInSameWeek($operation->getDate(), $this->lastOperationDate) === false) {
            return 0;
        }

        if ($this->weeklyWithdrawFreeOfChargeAmount > $operationAmountInEuro && $this->weeklyWithdrawFreeOfChargeOperations > 0) {
            return 0;
        }

        return $this->withdrawCommissionFee;
    }

    public function getFeeableAmount(float $amount): float
    {
        return abs($this->getWeeklyWithdrawFreeOfChargeAmount() - $amount);
    }
}
