<?php

namespace App\Adapter\RepositoryAdapter;

interface EntityRepositoryInterface
{

    public function findAll();

    public function findById($id);

    public function findAllBy(array $criteria): array;

    public function findOneBy(array $criteria);

    public function save($object);

    public function delete($object);

    public function execute();

}
