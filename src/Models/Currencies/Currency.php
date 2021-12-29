<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Currencies;

class Currency
{
    protected string $code;
    protected float  $rate;

    public function __construct(string $code, float $rate)
    {
        $this->code = $code;
        $this->rate = $rate;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
