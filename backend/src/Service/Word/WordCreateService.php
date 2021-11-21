<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Entity\Word;
use App\RequestModel\Word\WordRequest;
use App\ResponseModel\ResponseModelInterface;
use App\ResponseModel\Word\WordCreateResponse;
use App\Service\Authentication\TokenDecoderService;

class WordCreateService extends BaseWordService
{
    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService,
        WordRequest                 $wordRequest,
        WordCreateResponse          $wordCreateResponse
    )
    {
        parent::__construct($dictionaryRepositoryAdapter, $wordRepositoryAdapter, $tokenDecoderService);

        $this->requestModel = $wordRequest;
        $this->responseModel = $wordCreateResponse;

    }

    public function executeResponse(): ResponseModelInterface
    {
        if ($this->checkRequestIsValid()) {
            $this->create();
        }

        return $this->responseModel;
    }

    private function create()
    {
        $newWord = new Word();
        $newWord->setKnownLanguage($this->requestModel->knownLanguage);
        $newWord->setForeignLanguage($this->requestModel->foreignLanguage);

        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);
        $newWord->setDictionary($dictionary);

        $this->wordRepository->save($newWord);

        $this->responseModel->setResponseStatusCode(201);
        $this->responseModel->setResponseStatus(true);
    }
}
