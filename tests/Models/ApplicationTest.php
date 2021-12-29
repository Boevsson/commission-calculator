<?php

namespace Boevsson\CommissionTask\Tests\Models;

use Boevsson\CommissionTask\Application;
use Boevsson\CommissionTask\Models\Operations\Operation;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function dataProviderForImportCsv(): array
    {
        $input1 = dirname(__DIR__) . '/fixtures/input.csv';

        return [
            [$input1]
        ];
    }

    /**
     * @param string $csvFileName
     *
     * @dataProvider dataProviderForImportCsv
     */
    public function testParseCsv(string $csvFileName)
    {
        $application = new Application();
        $application->parseCsv($csvFileName);

        $operationsArray = $application->getOperationsArray();

        $this->assertCount(13, $operationsArray);

        foreach ($application->getOperations() as $operation) {
            $this->assertInstanceOf(Operation::class, $operation);
        }
    }

    public function dataProviderForSetUpUsers(): array
    {
        $input1 = dirname(__DIR__) . '/fixtures/input.csv';

        return [
            [$input1, 5]
        ];
    }

    /**
     * @param string $csvFileName
     * @param int $expectedUsersCount
     *
     * @dataProvider dataProviderForSetUpUsers
     */
    public function testSetUpUsers(string $csvFileName, int $expectedUsersCount)
    {
        $application = new Application();
        $application->addCurrency('EUR', 1);
        $application->addCurrency('USD', 1.1497);
        $application->addCurrency('JPY', 129.53);
        $application->parseCsv($csvFileName);
        $application->setUpUsers();

        $users = $application->getUsers();

        $this->assertCount($expectedUsersCount, $users);
    }

    public function dataProviderForSetUpOperations(): array
    {
        $input1 = dirname(__DIR__) . '/fixtures/input.csv';

        return [
            [$input1, 13]
        ];
    }

    /**
     * @param string $csvFileName
     * @param int $expectedOperationsCount
     *
     * @dataProvider dataProviderForSetUpOperations
     */
    public function testSetUpOperations(string $csvFileName, int $expectedOperationsCount)
    {
        $application = new Application();
        $application->addCurrency('EUR', 1);
        $application->addCurrency('USD', 1.1497);
        $application->addCurrency('JPY', 129.53);
        $application->parseCsv($csvFileName);
        $application->setUpUsers();
        $application->setUpOperations();

        $operations = $application->getOperations();

        $this->assertCount($expectedOperationsCount, $operations);
    }

    public function dataProviderForSetUp(): array
    {
        $input1 = dirname(__DIR__) . '/fixtures/input.csv';

        return [
            [$input1, 5, 13]
        ];
    }

    /**
     * @param string $csvFileName
     * @param int $expectedUsersCount
     * @param int $expectedOperationsCount
     *
     * @dataProvider dataProviderForSetUp
     */
    public function testSetUp(string $csvFileName, int $expectedUsersCount, int $expectedOperationsCount)
    {
        $application = new Application();
        $application->addCurrency('EUR', 1);
        $application->addCurrency('USD', 1.1497);
        $application->addCurrency('JPY', 129.53);
        $application->setUp($csvFileName);

        $users = $application->getUsers();
        $operations = $application->getOperations();

        $this->assertCount($expectedUsersCount, $users);
        $this->assertCount($expectedOperationsCount, $operations);
    }

    public function dataProviderForProcessOperations(): array
    {
        $input1         = dirname(__DIR__) . '/fixtures/input.csv';
        $commissionFees = [0.60, 3.00, 0.00, 0.06, 1.50, 0.00, 0.70, 0.30, 0.30, 3.00, 0.00, 0.00, 8611.41];

        return [
            [$input1, $commissionFees]
        ];
    }

    /**
     * @param string $csvFileName
     * @param array $expectedCommissionFees
     *
     * @dataProvider dataProviderForProcessOperations
     */
    public function testProcessOperations(string $csvFileName, array $expectedCommissionFees)
    {
        $application = new Application();
        $application->addCurrency('EUR', '1');
        $application->addCurrency('USD', '1.1497');
        $application->addCurrency('JPY', '129.53');
        $application->setUp($csvFileName);
        $commissionFees = $application->processOperations();

        $this->assertSame($expectedCommissionFees, $commissionFees);
    }
}
