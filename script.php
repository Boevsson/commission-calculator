<?php

include __DIR__ . '/vendor/autoload.php';

use \BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use Boevsson\CommissionTask\Models\OperationHandler;

if (isset($argv[1]) == false) {
    echo "Expects csv file name as parameter.";
    die();
}

$csvFileName = $argv[1];

//TODO: Move API key into an .env file
$access_key = '5e90be9a0f67b9c14d10d78ee5dbd73b';
$use_ssl = false; # Free plans are restricted to non-SSL only.

$lookup = new ExchangeRatesAPI($access_key, $use_ssl);
$rates  = $lookup->fetch();
$rates  = $lookup->addRate('USD')->addRate('JPY')->setBaseCurrency('EUR')->fetch();
$usdRate = $rates->getRate('USD');
$jpyRate = $rates->getRate('JPY');

$operationHandler = new OperationHandler(1000.00, 3);

$application = new \Boevsson\CommissionTask\CommissionCalculator($operationHandler);
$application->addCurrency('EUR', 1);
$application->addCurrency('USD', $usdRate);
$application->addCurrency('JPY', $jpyRate);
$application->setUp($csvFileName);

$commissionFees = $application->processOperations();

foreach ($commissionFees as $commissionFee) {
    echo number_format($commissionFee, 2, '.', '') . PHP_EOL;
}
