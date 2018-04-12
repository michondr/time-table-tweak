<?php

namespace App\Entity\Subject;

use Doctrine\ORM\EntityManagerInterface;

class SubjectFacade
{
    private $entityManager;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Subject::class);
    }

    public function insert(Subject $entity)
    {
        if ($entity->hasEmptyFields()) {
            return null;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(Subject $entity)
    {
        $inDb = $this->repository->findOneBy(
            [
                'indent' => $entity->getIndent(),
                'name' => $entity->getName(),
            ]
        );

        if (!is_null($inDb)) {
            return $inDb;
        }

        return $this->insert($entity);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }
}
