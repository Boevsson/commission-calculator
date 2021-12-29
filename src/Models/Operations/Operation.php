<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Operations;

use Boevsson\CommissionTask\Models\Currencies\Currency;
use Boevsson\CommissionTask\Models\User;

class Operation
{
    protected string   $date;
    protected float   $amount;
    protected Currency $currency;
    protected User     $user;

    public function __construct(string $date, float $amount, Currency $currency)
    {
        $this->date = $date;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
