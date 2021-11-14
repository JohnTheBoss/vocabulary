<?php

namespace App\Adapter\RepositoryAdapter;

interface EntityRepositoryInterface
{

    public function findAll();

    public function findById($id);

    public function findOneBy(array $criteria);

    public function save($object);

    public function execute();

}
