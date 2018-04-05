<?php

namespace App\Entity\TimeTable;

use Doctrine\ORM\EntityManagerInterface;

class TimeTableFacade
{
    private $entityManager;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(TimeTable::class);
    }

    public function insert(TimeTable $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(TimeTable $entity)
    {
        $inDb = $this->repository->findOneBy(
            [
                'subject' => $entity->getSubject(),
                'teacher' => $entity->getTeacher(),
                'location' => $entity->getLocation(),
                'day' => $entity->getDay(),
                'timeFrom' => $entity->getTimeFrom(),
                'timeTo' => $entity->getTimeTo(),
                'actionType' => $entity->getActionType(),
                'capacityFull' => $entity->getCapacityFull(),
                'capacityClass1' => $entity->getCapacityClass1(),
                'capacityClass2' => $entity->getCapacityClass2(),
                'capacityClass3' => $entity->getCapacityClass3(),
            ]
        );

        if (!is_null($inDb)) {
            return $inDb;
        }

        return $this->insert($entity);
    }
}
