<?php

namespace App\Service\Dictionary;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\Dictionary;
use App\ResponseModel\Dictionary\DictionaryResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class DictionaryWithWordsService extends AbstractRequestService
{
    private DictionaryRepositoryAdapter $dictionaryRepository;
    private TokenDecoderService $tokenDecoderService;
    private UserRepositoryAdapter $userRepository;

    private ?int $dictionaryId = null;

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        DictionaryResponseModel     $dictionaryResponseModel,
        UserRepositoryAdapter       $userRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService
    )
    {
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->responseModel = $dictionaryResponseModel;
        $this->userRepository = $userRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function setDictionaryId(int $id)
    {
        $this->dictionaryId = $id;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        $this->getDictionaryWithWords();
        return $this->responseModel;
    }

    private function getDictionaryWithWords()
    {
        if (empty($this->dictionaryId)) {
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseErrors(['A lekérdezésben az ID paraméter nincs megadva!!']);
            return;
        }

        /**
         * @var $dictionary Dictionary
         */
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);
        $user = $this->tokenDecoderService->getTokenData()->id;

        if (empty($dictionary)) {
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseErrors(['Nincs ilyen szótár!']);
            return;
        }

        if ($dictionary->getUser()->getId() !== $user) {
            $this->responseModel->setResponseStatusCode(403);
            $this->responseModel->setResponseErrors(['Nincs hozzáférésed az alábbi szótárhoz!']);
            return;
        }

        $this->responseModel->setResponseStatusCode(200);
        $this->responseModel->setResponseStatus(true);
        $this->responseModel->setDictionaryEntity($dictionary);

    }
}
