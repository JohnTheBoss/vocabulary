<?php

namespace App\ResponseModel\Word;

use App\ResponseModel\AbstractResponseModel;

class WordShowResponse extends AbstractResponseModel
{

    private ?WordResponse $word = null;

    public function setWordResponse(WordResponse $wordResponse)
    {
        $this->word = $wordResponse;
    }

    protected function responseData(): array
    {
        if (!empty($this->word)) {
            return [
                'word' => $this->word,
            ];
        }

        return [];
    }
}
