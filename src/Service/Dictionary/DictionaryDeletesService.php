<?php

namespace App\Service\Dictionary;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Entity\Dictionary;
use App\ResponseModel\Dictionary\DictionaryResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class DictionaryDeletesService extends AbstractRequestService
{

    private ?int $dictionaryId;
    private DictionaryRepositoryAdapter $dictionaryRepository;
    private WordRepositoryAdapter $wordRepository;
    private TokenDecoderService $tokenDecoderService;

    public function __construct(
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        WordRepositoryAdapter       $wordRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService,
        DictionaryResponseModel     $dictionaryResponseModel
    )
    {
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->responseModel = $dictionaryResponseModel;
        $this->wordRepository = $wordRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if (empty($this->dictionaryId)) {
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseErrors(['A lekérdezésben az ID paraméter nincs megadva!!']);
            return $this->responseModel;
        }

        $this->delete();

        return $this->responseModel;
    }

    public function setDictionaryId(int $id)
    {
        $this->dictionaryId = $id;
    }

    private function delete()
    {
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

        $words = $dictionary->getWords();
        foreach ($words as $word) {
            $this->wordRepository->delete($word);
        }

        $this->dictionaryRepository->delete($dictionary);

        $this->responseModel->setResponseStatusCode(200);
        $this->responseModel->setResponseStatus(true);
    }
}
