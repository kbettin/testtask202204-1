<?php

namespace App\CsvCalculator;

interface CsvCalculatorInterface
{
    /**
     * @throws \Exception
     */
    public function runCalculation(string $action): void;
}
