<?php

namespace App\ResponseModel\Dictionary;

use App\ResponseModel\AbstractResponseModel;

class DictionaryListResponseModel extends AbstractResponseModel
{

    /** @var DictionaryResponse[] $dictionariesList  */
    private array $dictionaries;

    public function setDictionariesResponse(array $dictionaries)
    {
        $this->dictionaries = $dictionaries;
    }

    protected function responseData(): array
    {
        return [
            'dictionaries' => $this->dictionaries,
        ];
    }
}
