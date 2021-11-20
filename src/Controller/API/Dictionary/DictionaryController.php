<?php

namespace App\Controller\API\Dictionary;

use App\Controller\API\AbstractAuthenticationBaseController;
use App\Service\Dictionary\DictionaryListService;
use App\Service\Dictionary\DictionaryService;
use App\Service\Dictionary\DictionaryWithWordsService;
use Symfony\Component\Routing\Annotation\Route;

class DictionaryController extends AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="dictionary_list",
     *     path="/dictionary",
     *     methods={"GET"}
     *     )
     */
    public function index(DictionaryListService $dictionaryListService)
    {
        $responseModel = $dictionaryListService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

    /**
     * @Route(
     *     name="dictionary_create",
     *     path="/dictionary",
     *     methods={"POST"}
     *     )
     */
    public function create(DictionaryService $dictionaryService)
    {
        $dictionaryService->setToCreate();
        $responseModel = $dictionaryService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

    /**
     * @Route(
     *     name="dictionary_get",
     *     path="/dictionary/{id}",
     *     methods={"GET"}
     *     )
     */
    public function getDictionary($id, DictionaryWithWordsService $dictionaryService)
    {
        $dictionaryService->setDictionaryId($id);
        $responseModel = $dictionaryService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

}
