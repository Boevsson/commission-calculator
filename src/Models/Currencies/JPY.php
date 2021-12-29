<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Currencies;

class JPY extends Currency
{
    public function __construct(float $rate)
    {
        parent::__construct('JPY', $rate);
    }
}
