<?php

namespace App\Entity\Teacher;

use Doctrine\ORM\EntityManagerInterface;

class TeacherFacade
{
    private $entityManager;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Teacher::class);
    }

    public function insert(Teacher $entity)
    {
        if ($entity->hasEmptyFields()) {
            return null;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(Teacher $entity)
    {
        $inDb = $this->repository->findOneBy(
            [
                'name' => $entity->getName(),
            ]
        );

        if (!is_null($inDb)) {
            return $inDb;
        }

        return $this->insert($entity);
    }
}
