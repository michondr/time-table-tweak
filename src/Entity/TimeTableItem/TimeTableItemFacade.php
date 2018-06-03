<?php

namespace App\Entity\TimeTableItem;

use App\Entity\Subject\Subject;
use App\TimeTableBuilder\Cell\Cell;
use App\TimeTableBuilder\Table\TimeTableInterval;
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

    public function getById(int $id)
    {
        return $this->repository->find($id);
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
     * @param Subject[] $subjects
     * @param bool|null $lectures - true -> only lectures, false -> only seminars, null -> both
     *
     * @return TimeTableItem[]|array
     */
    public function getBySubjects(array $subjects, bool $lectures = null)
    {
        if (is_null($lectures)) {
            $items = $this->repository->findBy(['subject' => $subjects]);
        } else {
            if ($lectures) {
                $items = $this->repository->findBy(['subject' => $subjects, 'actionType' => 'lecture']);
            } else {
                $items = $this->repository->findBy(['subject' => $subjects, 'actionType' => 'seminar']);
            }
        }

        foreach ($items as $key => $item) {
            if ($item->hasEmptyFields()) {
                unset($items[$key]);
            }
        }

        return $items;
    }

    public function getByCellData(Cell $cell)
    {
        $items = $this->repository->findBy(
            [
                'subject' => $cell->getSubject(),
                'actionType' => $cell->getActionType(),
                'day' => $cell->getDay(),
                'timeFrom' => TimeTableInterval::getIntervals()[$cell->getIdFrom()]->getFrom(),
                'timeTo' => TimeTableInterval::getIntervals()[$cell->getIdTo()]->getTo(),
            ]
        );

        foreach ($items as $key => $item) {
            if ($item->hasEmptyFields()) {
                unset($items[$key]);
            }
        }

        return $items;
    }
}
