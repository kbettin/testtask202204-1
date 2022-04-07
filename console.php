#!/usr/bin/env php
<?php

/**
 * Bridge script to trigger "csv calculation" process via old commandline command.
 *
 * Example call:
 *  php .\console.php --action plus --file .\test.csv
 */

require __DIR__ . '/vendor/autoload.php';

use App\Command\CsvCalculationCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

$application = new Application();

$command = new CsvCalculationCommand();
$application->add($command);

$inputDefinition = new InputDefinition([
    new InputOption('action', 'a', InputOption::VALUE_REQUIRED),
    new InputOption('file', 'f', InputOption::VALUE_REQUIRED),
]);

$argumentInput = new ArgvInput(null, $inputDefinition);

$application->run(new ArrayInput([
    'command' => $command->getName(),
    'action' => $argumentInput->getOption('action'),
    'inputFile' => $argumentInput->getOption('file')
]));
