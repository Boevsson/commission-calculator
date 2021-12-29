<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Currencies\Currency;

class Account
{
    private Currency $currency;
    private float $amount;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
        $this->amount = 0.00;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
