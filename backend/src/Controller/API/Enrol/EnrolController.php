<?php

namespace App\Controller\API\Enrol;

use App\Service\Enrol\EnrolDeleteService;
use App\Service\Enrol\EnrolService;
use Symfony\Component\Routing\Annotation\Route;

class EnrolController extends \App\Controller\API\AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="enrol_dictionary",
     *     path="/enrol/{id}",
     *     methods={"POST"}
     *     )
     */
    public function enrol($id, EnrolService $enrolService)
    {
        $enrolService->setDictionaryId($id);

        $responseModel = $enrolService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );

    }

    /**
     * @Route(
     *     name="enrol_dictionary",
     *     path="/enrol/{id}",
     *     methods={"DELETE"}
     *     )
     */
    public function unenrol($id, EnrolDeleteService $enrolDeleteService)
    {
        $enrolDeleteService->setDictionaryId($id);

        $responseModel = $enrolDeleteService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );

    }
}