<?php

namespace App\Command\DataFiller;

use App\XLSX\XlsxReaderBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataFillerCommand extends Command
{
    private $readerBuilder;

    public function __construct(
        XlsxReaderBuilder $readerBuilder
    ) {
        parent::__construct();
        $this->readerBuilder = $readerBuilder;
    }

    protected function configure()
    {
        $this
            ->setName('data:import:url')
            ->setDescription('imports data from xlsx online')
            ->addArgument('xlsx url', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $url = $input->getArgument('xlsx url');
        $url = 'https://drive.google.com/uc?export=download&id=0B9rw9mZ0OB2ZVlFQSUx6OFJMZ2NrTVlmdjduYlI5U1U1ek9Z';

        $xlsx = $this->readerBuilder->buildFromUrl($url);
        dump($xlsx->getSheetNames());
    }
}
