<?php

namespace App\Tests\CsvCalculator;

use App\CsvCalculator\CsvCalculator;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class CsvCalculatorTest extends TestCase
{
    /**
     * @dataProvider providerValid
     */
    public function testValid(string $inputCsvFilePath): void
    {
        vfsStream::setup('root');

        $baseFileName = basename($inputCsvFilePath, '.input');
        $action = $baseFileName;

        $object = new CsvCalculator($inputCsvFilePath, 'vfs://root/');
        $object->runCalculation($action);

        // assert result csv has been written and content matches with expected result
        $expectedResultFilePath = dirname($inputCsvFilePath) . sprintf('/%s.result', $baseFileName);
        $this->assertFileExists('vfs://root/result.csv');
        $this->assertFileEquals($expectedResultFilePath, 'vfs://root/result.csv');

        // assert log file if provided as fixture
        $expectedLogFilePath = dirname($inputCsvFilePath) . sprintf('/%s.log', $baseFileName);
        if (file_exists($expectedLogFilePath)) {
            $this->assertFileExists('vfs://root/log.txt');
            $this->assertFileEquals($expectedLogFilePath, 'vfs://root/log.txt');
        }
    }

    public function providerValid(): array
    {
        $fileList = glob(dirname(__FILE__, 2) . '/_fixtures/CsvCalculator/*.input');

        $dataSets = [];
        foreach ($fileList as $filePath) {
            $dataSets[basename($filePath)] = [
                $filePath,
            ];
        }
        return $dataSets;
    }
}
