<?php

namespace App\ResponseModel\Word;

use App\Entity\Word;

class WordResponse
{

    public ?int $id;
    public ?string $knownLanguage;
    public ?string $foreignLanguage;

    public function setFromEntity(Word $dictionary)
    {
        $this->id = $dictionary->getId();
        $this->knownLanguage = $dictionary->getKnownLanguage();
        $this->foreignLanguage = $dictionary->getForeignLanguage();
    }
}
