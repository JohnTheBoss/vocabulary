<?php

namespace App\Controller\API\Auth;

use App\Service\Authentication\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    /**
     * @Route(
     *     name="auth_login",
     *     path="/auth/login",
     *     methods={"POST", "GET"}
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


}
