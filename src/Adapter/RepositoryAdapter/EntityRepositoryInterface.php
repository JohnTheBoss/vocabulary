<?php

namespace App\Adapter\RepositoryAdapter;

interface EntityRepositoryInterface
{

    public function findAll();

    public function findOneBy(array $criteria);

    public function execute();

}
