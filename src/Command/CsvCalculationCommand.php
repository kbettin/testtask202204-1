<?php

namespace App\Command;

use App\CsvCalculator\CsvCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvCalculationCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('csv:calculation')
            ->addArgument('action', InputArgument::REQUIRED)
            ->addArgument('inputFile', InputArgument::REQUIRED)
            ->addArgument('outputDirectory', InputArgument::OPTIONAL, 'Optional output directory for results.', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $inputFile = $input->getArgument('inputFile');
            if (!is_string($inputFile)) {
                throw new \Exception('No or invalid parameter set for defining the input file!');
            }

            $action = $input->getArgument('action');
            if (!is_string($action)) {
                throw new \Exception('No or invalid parameter set for defining the action!');
            }

            $outputDirectory = $input->getArgument('outputDirectory');
            if (!is_string($outputDirectory)) {
                throw new \Exception('No or invalid parameter set for defining the output directory!');
            }

            $output->writeln(sprintf('Executing csv calculation "%s" for input file "%s"...', $action, $inputFile));
            $csvCalculator = new CsvCalculator($inputFile, $outputDirectory);
            $csvCalculator->runCalculation($action);
            $output->writeln('Done!');
        } catch (\Exception $exception) {
            $output->writeln(sprintf('Error processing csv calculation: %s', $exception->getMessage()));
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
