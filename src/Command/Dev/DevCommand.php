<?php

namespace App\Command\Dev;

use App\DateTime\DateTimeFactory;
use App\Entity\Location\Location;
use App\Entity\Location\LocationFacade;
use App\Entity\Subject\SubjectFacade;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\Cell\CellList;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DevCommand extends Command
{

    /**
     * @var LocationFacade
     */
    private $locationFacade;
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;
    /**
     * @var SubjectFacade
     */
    private $subjectFacade;
    /**
     * @var TimeTableBuilder
     */
    private $timeTableBuilder;
    /**
     * @var TimeTableItemFacade
     */
    private $timeTableItemFacade;

    public function __construct(
        LocationFacade $locationFacade,
    DateTimeFactory $dateTimeFactory,
    SubjectFacade $subjectFacade,
    TimeTableBuilder $timeTableBuilder,
    TimeTableItemFacade $timeTableItemFacade
    )
    {
        parent::__construct();
        $this->locationFacade = $locationFacade;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->subjectFacade = $subjectFacade;
        $this->timeTableBuilder = $timeTableBuilder;
        $this->timeTableItemFacade = $timeTableItemFacade;
    }

    protected function configure()
    {
        $this
            ->setName('dev:test')
            ->setDescription('dev command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        tstart('full');
        $indents = ['4IZ238', '4IZ278', '4ST201', '4IT101', '4EK112', '5EN101', '2AJ112'];
        $subjects = $this->subjectFacade->getByIndents($indents);
        $tables = $this->timeTableBuilder->getTimeTablesMulti($subjects);

            dump(count($tables));
        /** @var TimeTable $table */
        foreach ($tables as $table){
            dump($table->calculateIndex());
        }
        tend('full');
        die;
        dump(count($subjects));
        $items = $this->timeTableItemFacade->getBySubjects($subjects);
        dump(count($items));

        $cellList = CellList::constructFromTimeTableItems($items);
        dump(count($cellList->getCells()));
    }
}