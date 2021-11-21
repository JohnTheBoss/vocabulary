<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Entity\Dictionary;
use App\Entity\Word;
use App\RequestModel\Word\WordRequest;
use App\ResponseModel\ResponseModelInterface;
use App\ResponseModel\Word\WordCreateResponse;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class WordCreateService extends AbstractRequestService
{

    private DictionaryRepositoryAdapter $dictionaryRepository;
    private WordRepositoryAdapter $wordRepository;
    private TokenDecoderService $tokenDecoderService;
    private ?int $dictionaryId = null;

    public function __construct(
        WordRequest                 $wordRequest,
        WordCreateResponse          $wordCreateResponse,
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService
    )
    {
        $this->requestModel = $wordRequest;
        $this->responseModel = $wordCreateResponse;
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->wordRepository = $wordRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->dictionaryId === null) {
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['A paraméterben hiányzik a szótár ID!!']);

            return $this->responseModel;
        }

        if (!$this->checkDictionaryExists()) {
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['Nincs ilyen szótár!']);

            return $this->responseModel;
        }

        if (!$this->checkUserHasAccess()) {
            $this->responseModel->setResponseStatusCode(403);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['Nincs jogosultságod a szótárhoz, hogy hozzáadd a szót!']);

            return $this->responseModel;
        }

        if ($this->checkRequestIsValid()) {
            $this->create();
        }

        return $this->responseModel;
    }

    private function checkDictionaryExists()
    {
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);
        return !empty($dictionary);
    }

    private function checkUserHasAccess(): bool
    {
        $userId = $this->tokenDecoderService->getTokenData()->id;
        /** @var Dictionary $dictionary */
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);

        return ($dictionary->getUser()->getId() === $userId);
    }

    public function setDictionaryId($dictionaryId)
    {
        $this->dictionaryId = $dictionaryId;
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
