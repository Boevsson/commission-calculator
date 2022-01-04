<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Operations;

class WithdrawOperation extends Operation
{
    public function getCommissionFeeAmount(): float
    {
        $rate = $this->getCurrency()->getRate();

//        $rate = round($rate * 100) / 100;

        // Convert amount to Euro
        $amountInEuro = round($this->amount / $rate, 2);

        $feeableAmountInEuro = $this->getUser()->getFeeableAmount($amountInEuro);

        // Convert amount back to operation currency
        $feeableAmount = round($feeableAmountInEuro * $this->getCurrency()->getRate());

        $feeableAmountPennies = $feeableAmount * 100;

        $fee = $this->getUser()->getWithdrawCommissionFee($this) / 100;

        $commissionFeeAmountPennies = $feeableAmountPennies * $fee;

        $commissionFeeAmount = round($commissionFeeAmountPennies / 100, 2);

        $this->decrementUserWeeklyWithdrawFreeLimits($amountInEuro);

        return $commissionFeeAmount;
    }

    /**
     * @param $amountInEuro
     */
    private function decrementUserWeeklyWithdrawFreeLimits($amountInEuro): void
    {
        $newWeeklyWithdrawFreeOfChargeAmount = $this->getUser()->getWeeklyWithdrawFreeOfChargeAmount() - $amountInEuro;
        $this->getUser()->setWeeklyWithdrawFreeOfChargeAmount($newWeeklyWithdrawFreeOfChargeAmount);

        $newWeeklyWithdrawFreeOfChargeOperations = $this->getUser()->getWeeklyWithdrawFreeOfChargeOperations() - 1;
        $this->getUser()->setWeeklyWithdrawFreeOfChargeOperations($newWeeklyWithdrawFreeOfChargeOperations);
    }
}
