<?php

namespace App\ResponseModel\EnrolledList;

use App\Entity\DictionaryEnrol;
use App\ResponseModel\AbstractResponseModel;
use App\ResponseModel\Dictionary\DictionaryResponse;

class EnrolledListResponseModel extends AbstractResponseModel
{
    /* @var DictionaryEnrol[] $enrolledDictionaries */
    private array $enrolledDictionaries;


    public function setEnrolledDictionaries(array $enrolledDictionaries)
    {
        $this->enrolledDictionaries = $enrolledDictionaries;
    }

    protected function responseData(): array
    {
        return [
            'enrolled' => $this->getEnrolled()
            ,
        ];
    }

    private function getEnrolled()
    {
        $enrolled = [];

        foreach ($this->enrolledDictionaries as $enrolledDictionary) {
            $response = new DictionaryResponse();
            $response->setFromEntity($enrolledDictionary->getDictionary());
            $enrolled[] = $response;
        }

        return $enrolled;
    }
}