<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Service;

class DateService
{
    public static function checkIfDatesAreInSameWeek(string $firstDate, string $secondDate): bool
    {
        $d1 = strtotime($firstDate);
        $d2 = strtotime($secondDate);

        $d1w = date('oW', $d1);
        $d2w = date('oW', $d2);

        return $d1w === $d2w;
    }
}
