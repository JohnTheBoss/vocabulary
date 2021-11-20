<?php

namespace App\ResponseModel\Dictionary;

use App\Entity\Dictionary;

class DictionaryResponse
{
    public int $id;

    public string $name;

    public string $knownLanguage;

    public string $foreignLanguage;

    public bool $shared = false;

    public function setFromEntity(Dictionary $dictionary)
    {
        $this->id = $dictionary->getId();
        $this->name = $dictionary->getName();
        $this->knownLanguage = $dictionary->getKnownLanguage();
        $this->foreignLanguage = $dictionary->getForeignLanguage();
        $this->shared = false;
    }
}
