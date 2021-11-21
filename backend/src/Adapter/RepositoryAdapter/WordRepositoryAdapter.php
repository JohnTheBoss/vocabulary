<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\Word;

class WordRepositoryAdapter extends RepositoryAdapter
{

    public function getEntity(): string
    {
        return Word::class;
    }
}
