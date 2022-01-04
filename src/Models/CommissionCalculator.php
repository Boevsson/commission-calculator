<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Operations\Operation;
use Boevsson\CommissionTask\Service\DateService;

class CommissionCalculator extends Calculator
{
    private array $operations;
    private array $commissionFees;
    private float $weeklyWithdrawFreeOfChargeAmountThreshold;
    private int   $weeklyWithdrawFreeOfChargeOperationsCount;

    public function __construct(float $weeklyWithdrawFreeOfChargeAmountThreshold = 1000.00, int $weeklyWithdrawFreeOfChargeOperationsCount = 3)
    {
        $this->weeklyWithdrawFreeOfChargeAmountThreshold = $weeklyWithdrawFreeOfChargeAmountThreshold;
        $this->weeklyWithdrawFreeOfChargeOperationsCount = $weeklyWithdrawFreeOfChargeOperationsCount;
        $this->operations                                = [];
        $this->commissionFees                            = [];
    }

    public function addOperation(Operation $operation): void
    {
        $this->operations[] = $operation;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function processOperations(): array
    {
        foreach ($this->operations as $operation) {
            if ($this->checkShouldResetUserWeeklyWithdrawFreeLimits($operation)) {
                $this->resetUserWeeklyWithdrawFreeLimits($operation);
            }

            $commissionFeeAmount    = $operation->getCommissionFeeAmount();
            $this->commissionFees[] = $commissionFeeAmount;

            $operation->getUser()->setLastOperationDate($operation->getDate());
        }

        return $this->commissionFees;
    }

    protected function resetUserWeeklyWithdrawFreeLimits(Operation $operation)
    {
        $operation->getUser()->setWeeklyWithdrawFreeOfChargeOperations($this->weeklyWithdrawFreeOfChargeOperationsCount);
        $operation->getUser()->setWeeklyWithdrawFreeOfChargeAmount($this->weeklyWithdrawFreeOfChargeAmountThreshold);
    }

    protected function checkShouldResetUserWeeklyWithdrawFreeLimits($operation): bool
    {
        if (empty($operation->getUser()->getLastOperationDate())) {
            return false;
        }

        $areDatesInSameWeek = DateService::checkIfDatesAreInSameWeek($operation->getDate(), $operation->getUser()->getLastOperationDate());

        if ($areDatesInSameWeek === false) {
            return true;
        }

        return false;
    }
}
