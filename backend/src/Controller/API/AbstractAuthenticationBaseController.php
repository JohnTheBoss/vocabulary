<?php

namespace App\Controller\API;

use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\User;
use App\Service\Authentication\TokenDecoderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractAuthenticationBaseController extends AbstractController
{

    private ?User $userData;

    public function __construct(TokenDecoderService $tokenDecoderService, UserRepositoryAdapter $userRepositoryAdapter)
    {
        $user = $tokenDecoderService->getTokenData();
        if (!empty($user)) {
            $this->userData = $userRepositoryAdapter->findById($user->id);
        }

    }

    protected function getUser(): ?User
    {
        return $this->userData;
    }

}
