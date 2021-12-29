<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models\Operations;

use Boevsson\CommissionTask\Models\Currencies\Currency;
use Exception;

class OperationFactory
{
    /**
     * @throws Exception
     */
    public function createOperation(string $operationType, string $date, float $amount, Currency $currency)
    {
        switch ($operationType) {
            case 'deposit':
                return new DepositOperation($date, $amount, $currency);
            case 'withdraw':
                return new WithdrawOperation($date, $amount, $currency);
            default:
                throw new Exception('Error! Unknown operation type');
        }
    }
}
