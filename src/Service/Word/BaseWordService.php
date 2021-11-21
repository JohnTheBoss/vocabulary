<?php

namespace App\Service\Word;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Entity\Dictionary;
use App\ResponseModel\BaseResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

abstract class BaseWordService extends AbstractRequestService
{
    protected ?int $dictionaryId = null;

    protected DictionaryRepositoryAdapter $dictionaryRepository;
    protected WordRepositoryAdapter $wordRepository;
    protected TokenDecoderService $tokenDecoderService;

    protected ?int $wordId = null;

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService
    )
    {
        $this->responseModel = new BaseResponseModel();
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->wordRepository = $wordRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    abstract public function executeResponse(): ResponseModelInterface;

    public function setDictionaryId($dictionaryId)
    {
        $this->dictionaryId = $dictionaryId;
    }

    public function setWordId($wordId)
    {
        $this->wordId = $wordId;
    }

    protected function checkDictionaryExists(): bool
    {
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);
        return !empty($dictionary);
    }

    protected function checkWordExists(): bool
    {
        $word = $this->wordRepository->findOneBy(
            [
                'id' => $this->wordId,
                'dictionary' => $this->dictionaryId,
            ]
        );
        return !empty($word);
    }

    protected function checkUserHasAccess(): bool
    {
        $userId = $this->tokenDecoderService->getTokenData()->id;
        /** @var Dictionary $dictionary */
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);

        return ($dictionary->getUser()->getId() === $userId);
    }

    protected function testWordIdIsSetted()
    {
        if ($this->wordId === null) {
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['A paraméterben hiányzik a szó ID!!']);

            return $this->responseModel;
        }
    }

    protected function testDictionaryIdSetted()
    {
        if ($this->dictionaryId === null) {
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['A paraméterben hiányzik a szótár ID!!']);

            return $this->responseModel;
        }
    }

    protected function testDictionaryExists()
    {
        if (!$this->checkDictionaryExists()) {
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['Nincs ilyen szótár!']);

            return $this->responseModel;
        }
    }

    protected function testWordExists()
    {
        if (!$this->checkWordExists()) {
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['Nincs ilyen szó!']);

            return $this->responseModel;
        }
    }

    public function preTestResponse(): ?ResponseModelInterface
    {
        $this->testDictionaryIdSetted();

        $this->testDictionaryExists();

        if (!$this->checkUserHasAccess()) {
            $this->responseModel->setResponseStatusCode(403);
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors(['Nincs jogosultságod a szótárhoz, hogy hozzáadd a szót!']);

            return $this->responseModel;
        }

        return null;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        $preTest = $this->preTestResponse();
        if (!empty($preTest)) {
            return $preTest;
        }

        return $this->executeResponse();
    }

}
