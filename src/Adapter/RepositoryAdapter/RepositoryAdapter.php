<?php

namespace App\Adapter\RepositoryAdapter;

use Doctrine\ORM\EntityManagerInterface;

abstract class RepositoryAdapter implements EntityRepositoryInterface
{
    protected EntityManagerInterface $entityManager;

    protected $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->getEntity());
    }

    public abstract function getEntity(): string;

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function execute()
    {
        $this->entityManager->flush();
    }
}
