<?php

namespace App\Command\DataFiller;

use App\XLSX\XlsxReaderBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class DataFillerCommand extends Command
{
    const SOURCE_LOCAL = 'from local file';
    const SOURCE_URL = 'from url';

    private $readerBuilder;
    private $dataFiller;

    public function __construct(
        XlsxReaderBuilder $readerBuilder,
        DataFiller $dataFiller
    ) {
        parent::__construct();
        $this->readerBuilder = $readerBuilder;
        $this->dataFiller = $dataFiller;
    }

    protected function configure()
    {
        $this
            ->setName('data:import')
            ->setDescription('imports data from xlsx online');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $sourceQuestion = new ChoiceQuestion(
            'Select source of data file',
            [self::SOURCE_LOCAL, self::SOURCE_URL]
        );
        $source = $helper->ask($input, $output, $sourceQuestion);

        $pathQuestion = new Question(
            'Input absolute path to file: ',
            'https://drive.google.com/uc?export=download&id=0B9rw9mZ0OB2ZVlFQSUx6OFJMZ2NrTVlmdjduYlI5U1U1ek9Z'
        );
        $path = $helper->ask($input, $output, $pathQuestion);

        $xlsx = $this->getXlsx($source, $path);

        $sheetQuestion = new ChoiceQuestion(
            'Select source of data file',
            $xlsx->getSheetNames()
        );
        $sheet = $helper->ask($input, $output, $sheetQuestion);

        $data = $xlsx->getSheetData($sheet);

        array_shift($data);
        $sheetSize = count($data);

        $progressBar = new ProgressBar($output, $sheetSize);
        $progressBar->setFormat('debug');
        $progressBar->start();

        $i = 0;
        while ($i++ < $sheetSize) {
            $this->dataFiller->fillByRow($data[$i - 1]);
            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }

    private function getXlsx(string $source, string $path)
    {
        if ($source === self::SOURCE_LOCAL) {
            return $this->readerBuilder->buildFromPath($path);
        }
        if ($source === self::SOURCE_URL) {
            return $this->readerBuilder->buildFromUrl($path);
        }

        throw new \Exception('Unsupported source: '.$source);
    }
}
