<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Entity\Word;
use App\RequestModel\Word\WordEditRequest;
use App\ResponseModel\BaseResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\Authentication\TokenDecoderService;

class WordEditService extends BaseWordService
{

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService,
        BaseResponseModel           $baseResponseModel,
        WordEditRequest             $wordEditRequest
    )
    {
        parent::__construct($dictionaryRepositoryAdapter, $wordRepositoryAdapter, $tokenDecoderService);

        $this->responseModel = $baseResponseModel;
        $this->requestModel = $wordEditRequest;
    }

    public function executeResponse(): ResponseModelInterface
    {
        $wordExist = $this->testWordExists();

        if (!empty($wordExist)) {
            return $wordExist;
        }

        if ($this->checkRequestIsValid()) {
            $this->update();
        }

        return $this->responseModel;
    }

    private function update()
    {
        /** @var Word $word */
        $word = $this->wordRepository->findById($this->wordId);

        if (!empty($this->requestModel->foreignLanguage)) {
            $word->setForeignLanguage($this->requestModel->foreignLanguage);
        }

        if (!empty($this->requestModel->knownLanguage)) {
            $word->setKnownLanguage($this->requestModel->knownLanguage);
        }

        $this->wordRepository->save($word);

        $this->responseModel->setResponseStatus(true);
        $this->responseModel->setResponseStatusCode(200);

    }
}
