<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\ResponseModel\ResponseModelInterface;
use App\ResponseModel\Word\WordResponse;
use App\ResponseModel\Word\WordShowResponse;
use App\Service\Authentication\TokenDecoderService;

class WordShowService extends BaseWordService
{

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService,
        WordShowResponse            $wordShowResponse
    )
    {
        parent::__construct($dictionaryRepositoryAdapter, $wordRepositoryAdapter, $tokenDecoderService);

        $this->responseModel = $wordShowResponse;
    }

    public function executeResponse(): ResponseModelInterface
    {
        $this->testWordExists();

        $this->getItem();

        return $this->responseModel;
    }

    private function getItem()
    {
        $word = $this->wordRepository->findOneBy(
            [
                'id' => $this->wordId,
                'dictionary' => $this->dictionaryId,
            ]
        );

        $wordResponse = new WordResponse();
        $wordResponse->setFromEntity($word);

        $this->responseModel->setWordResponse($wordResponse);

    }
}
