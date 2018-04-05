<?php

namespace App\Command\Dev;

use App\DateTime\DateTimeFactory;
use App\Entity\Location\Location;
use App\Entity\Location\LocationFacade;
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

    public function __construct(
        LocationFacade $locationFacade,
    DateTimeFactory $dateTimeFactory
    )
    {
        parent::__construct();
        $this->locationFacade = $locationFacade;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    protected function configure()
    {
        $this
            ->setName('dev:test')
            ->setDescription('dev command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $str= 'Pá 12:45-14:15 ?? (př.)';

        dump($this->resolveBuilding($str));
        dump($this->resolveRoom($str));

        die;
        $data = explode(' ', 'Út 12:45-14:15 SB 105 (cv.)');
        $times = explode('-', $data[1]);

        dump($this->dateTimeFactory->fromFormat('H:i', $times[0])->getTime());
        dump($times);die;

        $location = new Location();

        $location->setRoom(123);
        $location->setBuilding('building1');

        $this->locationFacade->insertIfNotExist($location);

    }

    private function resolveBuilding(string $str)
    {
        $data = explode(' ', $str);
        $building = $data[2];

        if(strpos($building, '?') !== false){
            return null;
        }
        return $building;
    }

    private function resolveRoom(string $str)
    {
        $data = explode(' ', $str);

        return $data[3];
    }

}