<?php

namespace App\Repository;

use App\Entity\Dictionary;
use App\Entity\DictionaryEnrol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DictionaryEnrol|null find($id, $lockMode = null, $lockVersion = null)
 * @method DictionaryEnrol|null findOneBy(array $criteria, array $orderBy = null)
 * @method DictionaryEnrol[]    findAll()
 * @method DictionaryEnrol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DictionaryEnrolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DictionaryEnrol::class);
    }
}
