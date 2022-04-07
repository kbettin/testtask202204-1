<?php

namespace App\CsvCalculator;

use SplFileObject;

class CsvCalculator implements CsvCalculatorInterface
{
    private string $inputCsvFilePath;

    private string $outputDirectory;

    /**
     * @var string[]
     */
    private array $validActionList = [
        'division',
        'minus',
        'multiply',
        'plus',
    ];

    public function __construct(string $inputCsvFilePath, string $outputDirectory = '')
    {
        if (!file_exists($inputCsvFilePath)) {
            throw new \Exception(sprintf('Input file "%s" does not exist!', $inputCsvFilePath));
        }

        $this->inputCsvFilePath = $inputCsvFilePath;

        if (empty($outputDirectory)) {
            $outputDirectory = dirname($inputCsvFilePath);
        }

        $this->outputDirectory = $outputDirectory;
    }

    public function runCalculation(string $action): void
    {
        if (!in_array($action, $this->validActionList)) {
            $errorMessage = [];
            $errorMessage[] = sprintf('Provided action "%s" is not valid!', $action);
            $errorMessage[] = sprintf('Valid actions are: %s', implode(', ', $this->validActionList));
            throw new \Exception(implode(' ', $errorMessage));
        }

        $inputCsvHandle = fopen($this->inputCsvFilePath, 'r');
        if ($inputCsvHandle === false) {
            throw new \Exception(sprintf('Could not open input csv file "%s" for reading!', $this->inputCsvFilePath));
        }

        $resultCsvFilePath = sprintf('%s/result.csv', $this->outputDirectory);
        if (file_exists($resultCsvFilePath)) {
            throw new \Exception(sprintf('Result csv file "%s" already exists!', $resultCsvFilePath));
        }

        if (!is_dir(dirname($resultCsvFilePath))) {
            mkdir(dirname($resultCsvFilePath), 0775, true);
        }

        $outputCsvHandle = fopen($resultCsvFilePath, 'w');
        if ($outputCsvHandle === false) {
            throw new \Exception(sprintf('Could not open csv file "%s" for writing!', $this->inputCsvFilePath));
        }

        $resultLogFilePath = sprintf('%s/log.txt', $this->outputDirectory);

        file_put_contents($resultLogFilePath, sprintf('Started %s operation', $action) . PHP_EOL, FILE_APPEND);

        $inputCsvFile = new SplFileObject($this->inputCsvFilePath);
        $inputCsvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
        $inputCsvFile->setCsvControl(';');
        foreach ($inputCsvFile as $index => $csvRowData) {
            if (!is_array($csvRowData)) {
                continue;
            }

            if ($index === 0 && count($csvRowData) !== 2) {
                $errorMessage = [];
                $errorMessage[] = sprintf(
                    'Given input csv file "%s" must have exactly two columns!',
                    $this->inputCsvFilePath
                );
                $errorMessage[] = sprintf('%d columns found!', count($csvRowData));
                throw new \Exception(
                    implode(' ', $errorMessage)
                );
            }

            try {
                $value1 = intval($csvRowData[0]);
                $value2 = intval($csvRowData[1]);

                switch ($action) {
                    case 'division':
                        if ($value2 === 0) {
                            throw new \Exception(
                                sprintf('numbers are %d and %d are wrong, is not allowed', $value1, $value2)
                            );
                        }
                        $result = $value1 / $value2;
                        break;
                    case 'minus':
                        $result = $value1 - $value2;
                        break;
                    case 'multiply':
                        $result = $value1 * $value2;
                        break;
                    case 'plus':
                        $result = $value1 + $value2;
                        break;
                    default:
                        break;
                }

                if (!isset($result)) {
                    break;
                }

                if ($result < 0) {
                    throw new \Exception(sprintf('numbers %d and %d are wrong', $value1, $value2));
                }

                fputcsv($outputCsvHandle, [$value1, $value2, $result], ';');
            } catch (\Exception $exception) {
                // log error
                file_put_contents($resultLogFilePath, $exception->getMessage() . PHP_EOL, FILE_APPEND);
            }
        }

        file_put_contents($resultLogFilePath, sprintf('Finished %s operation', $action) . PHP_EOL, FILE_APPEND);
        fclose($inputCsvHandle);
        fclose($outputCsvHandle);
    }
}
