<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\Dictionary;
use App\Entity\DictionaryEnrol;
use Doctrine\ORM\Query\Expr\Join;

class DictionaryEnrolRepositoryAdapter extends RepositoryAdapter
{

    public function getEntity(): string
    {
        return DictionaryEnrol::class;
    }

    /**
     * @return Dictionary[]
     */
    public function getNotEnrolledDictionaries(int $userId)
    {

        $qb = $this->entityManager->createQueryBuilder()
            ->select('d')
            ->from(Dictionary::class, 'd')
            ->leftJoin(DictionaryEnrol::class, 'de', Join::WITH, 'de.dictionary = d.id AND de.user = :userId')
            ->where('de.dictionary IS NULL')
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }
}