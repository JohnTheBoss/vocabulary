<?php

namespace App\Controller\API\Word;

use App\Controller\API\AbstractAuthenticationBaseController;
use App\Service\Word\WordCreateService;
use App\Service\Word\WordShowService;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="word_create",
     *     path="/dictionary/{dictionaryId}/word",
     *     methods={"POST"}
     *     )
     */
    public function create($dictionaryId, WordCreateService $wordCreateService)
    {
        $wordCreateService->setDictionaryId($dictionaryId);
        $responseModel = $wordCreateService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

    /**
     * @Route(
     *     name="word_show",
     *     path="/dictionary/{dictionaryId}/word/{wordId}",
     *     methods={"GET"}
     *     )
     */
    public function show($dictionaryId, $wordId, WordShowService $wordShowService)
    {
        $wordShowService->setDictionaryId($dictionaryId);
        $wordShowService->setWordId($wordId);
        $responseModel = $wordShowService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }
}
