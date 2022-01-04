<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Boevsson\CommissionTask\Models\Operations\Operation;

abstract class Calculator
{
    abstract public function addOperation(Operation $operation);

    abstract public function getOperations();

    abstract public function processOperations();
}
