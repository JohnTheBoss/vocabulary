<?php

namespace App\ResponseModel\Dictionary;

use App\Entity\Dictionary;
use App\Entity\Word;
use App\ResponseModel\AbstractResponseModel;

class DictionaryResponseModel extends AbstractResponseModel
{
    private array $dictionary;

    public function setDictionaryEntity(Dictionary $dictionary)
    {
        $this->dictionary = [];
        $this->dictionary['id'] = $dictionary->getId();
        $this->dictionary['name'] = $dictionary->getName();
        $this->dictionary['knownLanguage'] = $dictionary->getKnownLanguage();
        $this->dictionary['foreignLanguage'] = $dictionary->getForeignLanguage();

        $words = array_map(
            /** @var Word $word */
            function ($word){
                return [
                    'id' => $word->getId(),
                    'knownLanguage' => $word->getKnownLanguage(),
                    'foreignLanguage' => $word->getForeignLanguage(),
                ];
            },
            $dictionary->getWords()->toArray()
        );

        $this->dictionary['words'] = $words;
    }

    protected function responseData(): array
    {
        if (!empty($this->dictionary)) {
            return [
                'dictionary' => $this->dictionary,
            ];
        }
        return [];
    }
}
