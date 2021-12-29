<?php

include __DIR__ . '/vendor/autoload.php';

use \BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;

if (isset($argv[1]) == false) {
    echo "Expects csv file name as parameter.";
    die();
}

$csvFileName = $argv[1];

$access_key = '5e90be9a0f67b9c14d10d78ee5dbd73b';
$use_ssl = false; # Free plans are restricted to non-SSL only.

$lookup = new ExchangeRatesAPI($access_key, $use_ssl);
$rates  = $lookup->fetch();
$rates  = $lookup->addRate('USD')->addRate('JPY')->setBaseCurrency('EUR')->fetch();
$usdRate = $rates->getRate('USD');
$jpyRate = $rates->getRate('JPY');

$application = new \Boevsson\CommissionTask\Application();
$application->addCurrency('EUR', 1);
$application->addCurrency('USD', $usdRate);
$application->addCurrency('JPY', $jpyRate);
$application->setUp($csvFileName);

$commissionFees = $application->processOperations();

foreach ($commissionFees as $commissionFee) {
    echo number_format($commissionFee, 2, '.', '') . PHP_EOL;
}
