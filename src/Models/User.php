<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Operations\Operation;

abstract class User
{
    protected int    $id;
    protected array  $accounts;
    protected array  $operations;
    protected float  $depositCommissionFee;
    protected float  $withdrawCommissionFee;
    protected float  $weeklyWithdrawFreeOfChargeAmount;
    protected int    $weeklyWithdrawFreeOfChargeOperations;
    protected string $lastOperationDate;

    public function __construct(int $id, float $depositCommissionFee, float $withdrawCommissionFee, float $weeklyWithdrawFreeOfChargeAmount = 0, int $weeklyWithdrawFreeOfChargeOperations = 0)
    {
        $this->id                                   = $id;
        $this->depositCommissionFee                 = $depositCommissionFee;
        $this->withdrawCommissionFee                = $withdrawCommissionFee;
        $this->accounts                             = [];
        $this->operations                           = [];
        $this->lastOperationDate                    = '';
        $this->weeklyWithdrawFreeOfChargeAmount     = $weeklyWithdrawFreeOfChargeAmount;
        $this->weeklyWithdrawFreeOfChargeOperations = $weeklyWithdrawFreeOfChargeOperations;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function addAccount(Account $account)
    {
        foreach ($this->accounts as $element) {
            if ($element->getCurrency()->getCode() === $account->getCurrency()->getCode()) {
                return;
            }
        }

        $this->accounts[] = $account;
    }

    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function getDepositCommissionFee(): float
    {
        return $this->depositCommissionFee;
    }

    public function getWithdrawCommissionFee(Operation $operation): float
    {
        return $this->withdrawCommissionFee;
    }

    public function getWeeklyWithdrawFreeOfChargeAmount(): float
    {
        return $this->weeklyWithdrawFreeOfChargeAmount;
    }

    public function getWeeklyWithdrawFreeOfChargeOperations(): int
    {
        return $this->weeklyWithdrawFreeOfChargeOperations;
    }

    public function setLastOperationDate(string $lastOperationDate): void
    {
        $this->lastOperationDate = $lastOperationDate;
    }

    /**
     * @return string
     */
    public function getLastOperationDate(): ?string
    {
        return $this->lastOperationDate;
    }

    public function setWeeklyWithdrawFreeOfChargeAmount(float $weeklyWithdrawFreeOfChargeAmount): void
    {
        if ($weeklyWithdrawFreeOfChargeAmount < 0.00) {
            $this->weeklyWithdrawFreeOfChargeAmount = 0.00;

            return;
        }

        $this->weeklyWithdrawFreeOfChargeAmount = $weeklyWithdrawFreeOfChargeAmount;
    }

    public function setWeeklyWithdrawFreeOfChargeOperations(int $weeklyWithdrawFreeOfChargeOperations): void
    {
        if ($weeklyWithdrawFreeOfChargeOperations < 0) {
            $this->weeklyWithdrawFreeOfChargeOperations = 0;

            return;
        }

        $this->weeklyWithdrawFreeOfChargeOperations = $weeklyWithdrawFreeOfChargeOperations;
    }
}
