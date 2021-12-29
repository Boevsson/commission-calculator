<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask;

use Boevsson\CommissionTask\Models\Account;
use Boevsson\CommissionTask\Models\CommissionCalculator;
use Boevsson\CommissionTask\Models\Currencies\Currency;
use Boevsson\CommissionTask\Models\Operations\OperationFactory;
use Boevsson\CommissionTask\Models\User;
use Boevsson\CommissionTask\Models\UserFactory;
use Exception;

class Application
{
    private array $operationsArray = [];
    private array $operations      = [];
    private array $users           = [];
    private array $currencies      = [];
    private float $weeklyWithdrawFreeOfChargeAmountThreshold;
    private int   $weeklyWithdrawFreeOfChargeOperationsCount;

    public function __construct(float $weeklyWithdrawFreeOfChargeAmountThreshold = 1000.00, int $weeklyWithdrawFreeOfChargeOperationsCount = 3)
    {
        $this->weeklyWithdrawFreeOfChargeAmountThreshold = $weeklyWithdrawFreeOfChargeAmountThreshold;
        $this->weeklyWithdrawFreeOfChargeOperationsCount = $weeklyWithdrawFreeOfChargeOperationsCount;
    }

    /**
     * @throws Exception
     */
    public function setUp(string $csvFileName)
    {
        $this->parseCsv($csvFileName);
        $this->setUpUsers();
        $this->setUpOperations();
    }

    public function parseCsv(string $csvFileName)
    {
        //TODO: Validate CSV

        $row = 1;

        if (($handle = fopen($csvFileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                ++$row;

                $this->operationsArray[] = [
                    'date'           => $data[0],
                    'user_id'        => (int) $data[1],
                    'user_type'      => $data[2],
                    'operation_type' => $data[3],
                    'amount'         => (float) $data[4],
                    'currency_code'  => $data[5],
                ];
            }

            fclose($handle);
        }
    }

    public function addCurrency(string $currencyCode, float $currencyRate)
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getCode() === $currencyCode) {
                return;
            }
        }

        $this->currencies[] = new Currency($currencyCode, $currencyRate);
    }

    public function processOperations(): array
    {
        $commissionCalculator = new CommissionCalculator($this->weeklyWithdrawFreeOfChargeAmountThreshold, $this->weeklyWithdrawFreeOfChargeOperationsCount);

        foreach ($this->operations as $operation) {
            $commissionCalculator->addOperation($operation);
        }

        return $commissionCalculator->processOperations();
    }

    /**
     * @throws Exception
     */
    public function setUpOperations()
    {
        foreach ($this->operationsArray as $operationArray) {
            $operation = $this->createOperation($operationArray['operation_type'], $operationArray['date'], $operationArray['amount'], $operationArray['currency_code']);

            $user = $this->findUserById($operationArray['user_id']);

            if (!$user) {
                throw new Exception(sprintf('User with Id: %s not found.', $operationArray['user_id']));
            }

            $operation->setUser($user);

            $this->operations[] = $operation;
        }
    }

    /**
     * @throws Exception
     */
    public function createOperation(string $operationType, string $date, float $amount, string $currencyCode)
    {
        $operationFactory = new OperationFactory();

        $currency = $this->findCurrencyByCode($currencyCode);

        return $operationFactory->createOperation($operationType, $date, $amount, $currency);
    }

    /**
     * @throws Exception
     */
    public function setUpUsers()
    {
        foreach ($this->operationsArray as $operation) {
            if ($this->findUserById($operation['user_id'])) {
                continue;
            }

            $user = $this->createUser($operation['user_id'], $operation['user_type']);

            $this->users[] = $user;

            $currency = $this->findCurrencyByCode($operation['currency_code']);

            $account = new Account($currency);

            $user->addAccount($account);
        }
    }

    /**
     * @throws Exception
     */
    public function createUser($userId, $userType): User
    {
        $userFactory = new UserFactory();

        return $userFactory->createUser($userId, $userType);
    }

    public function findCurrencyByCode(string $currencyCode)
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getCode() === $currencyCode) {
                return $currency;
            }
        }

        return false;
    }

    public function findUserById(int $userId)
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $userId) {
                return $user;
            }
        }

        return false;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function getOperationsArray(): array
    {
        return $this->operationsArray;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }
}
