<?php

namespace App\Controller\API\AvailableDictionaries;

use App\Adapter\RepositoryAdapter\DictionaryEnrolRepositoryAdapter;
use App\Repository\DictionaryEnrolRepository;
use App\ResponseModel\AvailableDictionaries\AvailableDictionariesResponseModel;
use App\Service\Authentication\TokenDecoderService;
use Symfony\Component\Routing\Annotation\Route;

class AvailableDictionaries extends \App\Controller\API\AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="available_dictionaries_list",
     *     path="/availableDictionaries",
     *     methods={"GET"}
     *     )
     */
    public function list(DictionaryEnrolRepositoryAdapter $dictionaryEnrolRepository, TokenDecoderService $tokenDecoderService)
    {
        $userId = $tokenDecoderService->getTokenData()->id;

        $responseModel = new AvailableDictionariesResponseModel();
        $responseModel->setCurrentUserId($userId);

        $dictionaries = $dictionaryEnrolRepository->getNotEnrolledDictionaries($userId);

        $responseModel->setDictionariesFromEntity($dictionaries);

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }
}