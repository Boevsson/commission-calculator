<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Operations;

class WithdrawOperation extends Operation
{
    public function getCommissionFeeAmount(): float
    {
        // Convert amount to Euro
        $amountInEuro = $this->amount / $this->getCurrency()->getRate();

        $feeableAmountInEuro = $this->getUser()->getFeeableAmount($amountInEuro);

        // Convert amount back to operation currency
        $feeableAmount = $feeableAmountInEuro * $this->getCurrency()->getRate();

        $feePercentage = $this->getUser()->getWithdrawCommissionFee($this);

        $commissionFeeAmount = ceil($feeableAmount * $feePercentage);

        $commissionFeeAmount = $commissionFeeAmount / 100;

        // Decrement user weekly withdraw free limits
        $newWeeklyWithdrawFreeOfChargeAmount =  $this->getUser()->getWeeklyWithdrawFreeOfChargeAmount() - $amountInEuro;
        $this->getUser()->setWeeklyWithdrawFreeOfChargeAmount($newWeeklyWithdrawFreeOfChargeAmount);

        $newWeeklyWithdrawFreeOfChargeOperations = $this->getUser()->getWeeklyWithdrawFreeOfChargeOperations() - 1;
        $this->getUser()->setWeeklyWithdrawFreeOfChargeOperations($newWeeklyWithdrawFreeOfChargeOperations);

        return $commissionFeeAmount;
    }
}
