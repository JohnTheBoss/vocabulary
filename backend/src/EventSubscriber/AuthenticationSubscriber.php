<?php

namespace App\EventSubscriber;

use App\Controller\API\AbstractAuthenticationBaseController;
use App\ResponseModel\StatusResponse;
use App\Service\Authentication\TokenDecoderService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthenticationSubscriber implements EventSubscriberInterface
{

    private TokenDecoderService $tokenDecoderService;

    public function __construct(TokenDecoderService $tokenDecoderService)
    {
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AbstractAuthenticationBaseController) {
            $tokenIsValid = $this->tokenDecoderService->validateToken();
            if ($tokenIsValid !== TokenDecoderService::TOKEN_VALID) {
                $status = new StatusResponse();
                $status->success = false;

                switch ($tokenIsValid) {
                    case TokenDecoderService::TOKEN_NOT_EXIST:
                        $status->errors[] = 'Nem került megadásra a HTTP Headers-ben az authorization rész!.';
                        break;
                    case TokenDecoderService::TOKEN_EXPIRED:
                        $status->errors[] = 'Lejárt az időkorlát, jelentkezz be újra!';
                        break;
                    case TokenDecoderService::TOKEN_INVALID:
                        $status->errors[] = 'Hibás vagy érvénytelen token!';
                        break;
                    default:
                        $status->errors[] = 'Nem várt hiba történt.';
                        break;
                }

                $event->setController(
                    function () use ($status) {
                        return new JsonResponse($status, 401);
                    }
                );
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
