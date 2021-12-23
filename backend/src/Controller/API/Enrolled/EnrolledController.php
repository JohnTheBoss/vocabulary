<?php

namespace App\Controller\API\Enrolled;

use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\ResponseModel\EnrolledList\EnrolledListResponseModel;
use App\Service\Authentication\TokenDecoderService;
use Symfony\Component\Routing\Annotation\Route;

class EnrolledController extends \App\Controller\API\AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="enrolled_list",
     *     path="/enrolled",
     *     methods={"GET"}
     *     )
     */
    public function getEnrolled(TokenDecoderService $tokenDecoderService, UserRepositoryAdapter $userRepository)
    {
        $userId = $tokenDecoderService->getTokenData()->id;
        $user = $userRepository->findById($userId);
        $userEnroll = $user->getDictionaryEnrols()->toArray();

        $responseModel = new EnrolledListResponseModel();
        $responseModel->setEnrolledDictionaries($userEnroll);

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

}