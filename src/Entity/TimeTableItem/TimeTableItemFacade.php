<?php

namespace App\Entity\TimeTableItem;

use App\Entity\Subject\Subject;
use Doctrine\ORM\EntityManagerInterface;

class TimeTableItemFacade
{
    private $entityManager;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(TimeTableItem::class);
    }

    public function insert(TimeTableItem $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(TimeTableItem $entity)
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

    /**
     * @param array     $subjects
     * @param bool|null $lectures - true -> only lectures, false -> only seminars, null -> both
     *
     * @return TimeTableItem[]|array
     */
    public function getBySubjects(array $subjects, array $days, bool $lectures = null)
    {
        $ids = [];

        /** @var Subject $subject */
        foreach ($subjects as $subject) {
            $ids[] = $subject->getId();
        }

        if (is_null($lectures)) {
            $items = $this->repository->findBy(['subject' => $ids, 'day' => $days]);
        } else {
            if ($lectures) {
                $items = $this->repository->findBy(['subject' => $ids, 'day' => $days, 'actionType' => 'lecture']);
            } else {
                $items = $this->repository->findBy(['subject' => $ids, 'day' => $days, 'actionType' => 'seminar']);
            }
        }

        shuffle($items);
        return $items;
    }
}
