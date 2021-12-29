<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Currencies;

class EUR extends Currency
{
    public function __construct(float $rate)
    {
        parent::__construct('EUR', $rate);
    }
}
