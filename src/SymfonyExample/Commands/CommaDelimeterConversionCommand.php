<?php
namespace SymfonyExample\Commands;

use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommaDelimeterConversionCommand extends Command
{
    protected function configure()
    {
        $this->setName('convert-to-commas');
        $this->setDescription('Converts tab-delimited into comma-delimited ones.');

        $this->addArgument('file', InputArgument::REQUIRED, 'file to convert');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buffer = array();
        $file = new SplFileObject($input->getArgument('file'));

        while (!$file->eof()) {
            $line = $file->fgets();
            $csv_line = str_replace("\t", ",", $line);
            $buffer[] = $csv_line;
        }

        $output->write($buffer);
        $file = null;
    }
}