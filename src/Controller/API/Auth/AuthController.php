<?php

namespace App\Controller\API\Auth;

use App\Service\Authentication\AuthenticationService;
use App\Service\Authentication\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    /**
     * @Route(
     *     name="auth_login",
     *     path="/auth/login",
     *     methods={"POST"}
     *     )
     */
    public function login(AuthenticationService $authenticationService)
    {
        $responseModel = $authenticationService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

    /**
     * @Route(
     *     name="auth_register",
     *     path="/auth/register",
     *     methods={"POST"}
     *     )
     */
    public function register(RegisterService $registerService)
    {
        $responseModel = $registerService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }


}
