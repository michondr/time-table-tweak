<?php

namespace App\Entity\User;

use App\DateTime\DateTimeFactory;
use Doctrine\ORM\EntityManagerInterface;

class UserFacade
{
    private $entityManager;
    private $repository;
    private $dateTimeFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function insert(User $entity)
    {
        if (is_null($entity->getAddedAt())) {
            $entity->setAddedAt($this->dateTimeFactory->now());
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function update(User $entity)
    {
        $this->entityManager->merge($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insertIfNotExist(User $entity)
    {
        $inDb = $this->repository->findOneBy(
            [
                'username' => $entity->getUsername(),
                'email' => $entity->getEmail(),
            ]
        );

        if (!is_null($inDb)) {
            throw new \Exception('Inserting duplicitous user');
        }

        return $this->insert($entity);
    }

    public function isReadyToSave(User $entity)
    {
        $inDbByMail = $this->repository->findOneBy(
            [
                'email' => $entity->getEmail(),
            ]
        );

        $inDbByName = $this->repository->findOneBy(
            [
                'username' => $entity->getUsername(),
            ]
        );

        if (!is_null($inDbByMail) or !is_null($inDbByName)) {
            return true;
        }

        return false;
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function changeStatus(int $id)
    {
        $user = $this->getById($id);

        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            return false;
        }
        $user->setIsActive(!$user->isActive());
        $this->update($user);

        return true;
    }
}
