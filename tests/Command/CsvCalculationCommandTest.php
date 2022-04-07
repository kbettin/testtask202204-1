<?php

namespace App\Tests\Command;

use App\Command\CsvCalculationCommand;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CsvCalculationCommandTest extends TestCase
{
    public function testValid(): void
    {
        vfsStream::setup('root');

        $application = new Application();
        $application->add(new CsvCalculationCommand());
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        $inputFile = dirname(__FILE__, 2) . '/_fixtures/Command/CsvCalculation/plus.csv';

        $tester->run([
            'command' => 'csv:calculation',
            'action' => 'plus',
            'inputFile' => $inputFile,
            'outputDirectory' => 'vfs://root'
        ]);

        $display = $tester->getDisplay();

        $expectedDisplay = [
            sprintf('Executing csv calculation "plus" for input file "%s"...', $inputFile),
            'Done!'
        ];
        foreach ($expectedDisplay as $expectedDisplayLine) {
            $this->assertStringContainsString($expectedDisplayLine, $display);
        }

        $this->assertFileExists('vfs://root/result.csv');
        $this->assertFileEquals(
            dirname(__FILE__, 2) . '/_fixtures/Command/CsvCalculation/plus-result.csv',
            'vfs://root/result.csv'
        );
        $this->assertFileExists('vfs://root/log.txt');
        $this->assertFileEquals(
            dirname(__FILE__, 2) . '/_fixtures/Command/CsvCalculation/plus-log.txt',
            'vfs://root/log.txt'
        );
    }
}
