<?php

namespace App\Entity\Location;

use Doctrine\ORM\EntityManagerInterface;

class LocationFacade
{
    private $entityManager;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Location::class);
    }

    public function insert(Location $entity)
    {
        if ($entity->hasEmptyFields()) {
            return null;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(Location $entity)
    {
        $inDb = $this->repository->findOneBy(
            [
                'building' => $entity->getBuilding(),
                'room' => $entity->getRoom(),
            ]
        );

        if (!is_null($inDb)) {
//            return $inDb;
        }

        return $this->insert($entity);
    }
}
