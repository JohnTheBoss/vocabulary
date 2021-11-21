<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\ResponseModel\BaseResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\Authentication\TokenDecoderService;

class WordDeleteService extends BaseWordService
{

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService,
        BaseResponseModel           $baseResponseModel
    )
    {
        parent::__construct($dictionaryRepositoryAdapter, $wordRepositoryAdapter, $tokenDecoderService);

        $this->responseModel = $baseResponseModel;
    }

    public function executeResponse(): ResponseModelInterface
    {
        $wordExist = $this->testWordExists();

        if (!empty($wordExist)) {
            return $wordExist;
        }

        $this->delete();

        return $this->responseModel;
    }

    private function delete()
    {
        $word = $this->wordRepository->findById($this->wordId);

        $this->wordRepository->delete($word);

        $this->responseModel->setResponseStatusCode(200);
        $this->responseModel->setResponseStatus(true);
    }
}
