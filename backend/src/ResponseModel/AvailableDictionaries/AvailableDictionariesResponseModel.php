<?php

namespace App\ResponseModel\AvailableDictionaries;

use App\Entity\Dictionary;
use App\ResponseModel\AbstractResponseModel;
use App\ResponseModel\Dictionary\DictionaryResponse;

class AvailableDictionariesResponseModel extends AbstractResponseModel
{
    /* @var Dictionary[] */
    private array $dictionaries;

    private int $currentUserId = 0;

    /**
     * @param Dictionary[] $dictionaries
     */
    public function setDictionariesFromEntity(array $dictionaries)
    {
        $this->dictionaries = $dictionaries;
    }

    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;
    }

    protected function responseData(): array
    {
        return [
            'dictionaries' => $this->getDictionaries(),
        ];
    }

    private function getDictionaries()
    {
        $array = [];

        foreach ($this->dictionaries as $dictionary) {
            $returnData = new DictionaryResponse();
            $returnData->setFromEntity($dictionary);
            $returnData->yours = $dictionary->getUser()->getId() === $this->currentUserId;

            $array[] = $returnData;
        }

        return $array;
    }
}