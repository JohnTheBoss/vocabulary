<?php

namespace App\Service\Dictionary;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\ResponseModel\Dictionary\DictionaryListResponseModel;
use App\ResponseModel\Dictionary\DictionaryResponse;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class DictionaryListService extends AbstractRequestService
{
    private DictionaryRepositoryAdapter $dictionaryRepository;
    private TokenDecoderService $tokenDecoderService;

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        DictionaryListResponseModel $dictionaryListResponseModel,
        TokenDecoderService         $tokenDecoderService
    )
    {
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->responseModel = $dictionaryListResponseModel;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        $this->userDictionaryList();

        return $this->responseModel;
    }

    private function userDictionaryList()
    {
        $userId = $this->tokenDecoderService->getTokenData()->id;

        $dictionaries = $this->dictionaryRepository->findAllBy(['user' => $userId]);

        $dictionariesList = array_map(
            function ($dictionary) {
                $dictionaryResponse = new DictionaryResponse();
                $dictionaryResponse->setFromEntity($dictionary);
                return $dictionaryResponse;
            },
            $dictionaries
        );

        $this->responseModel->setResponseStatus(true);
        $this->responseModel->setResponseStatusCode(200);
        $this->responseModel->setDictionariesResponse($dictionariesList);

    }
}
