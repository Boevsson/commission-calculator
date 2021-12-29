<?php

include __DIR__ . '/vendor/autoload.php';

if (isset($argv[1]) == false) {
    echo "Expects csv file name as parameter.";
    die();
}

$csvFileName = $argv[1];

$application = new \Boevsson\CommissionTask\Application();
$application->addCurrency('EUR', '1');
$application->addCurrency('USD', '1.1497');
$application->addCurrency('JPY', '129.53');
$application->setUp($csvFileName);

$commissionFees = $application->processOperations();

foreach ($commissionFees as $commissionFee) {
    echo $commissionFee . PHP_EOL;
}
