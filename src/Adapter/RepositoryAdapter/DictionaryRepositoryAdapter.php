<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\Dictionary;

class DictionaryRepositoryAdapter extends RepositoryAdapter
{

    public function getEntity(): string
    {
        return Dictionary::class;
    }
}
