<?php

namespace App\EventSubscriber;

use App\Adapter\AuthenticationAdapter\JWTAdapterInterface;
use App\Controller\API\AbstractAuthenticationBaseController;
use App\ResponseModel\StatusResponse;
use Firebase\JWT\ExpiredException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthenticationSubscriber implements EventSubscriberInterface
{

    private const HEADER_TOKEN_PATTERN = "/Bearer\s+(.*)$/i";

    private const TOKEN_VALID = 1;
    private const TOKEN_NOT_EXIST = -1;
    private const TOKEN_EXPIRED = -2;
    private const TOKEN_INVALID = -3;

    private JWTAdapterInterface $JWTAdapter;

    public function __construct(JWTAdapterInterface $JWTAdapter)
    {
        $this->JWTAdapter = $JWTAdapter;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AbstractAuthenticationBaseController) {
            $tokenIsValid = $this->validateToken($event->getRequest());
            if ($tokenIsValid !== self::TOKEN_VALID) {
                $status = new StatusResponse();
                $status->success = false;

                switch ($tokenIsValid) {
                    case self::TOKEN_NOT_EXIST:
                        $status->errors[] = 'Nem került megadásra a HTTP Headers-ben az authorization rész!.';
                        break;
                    case self::TOKEN_EXPIRED:
                        $status->errors[] = 'Lejárt az időkorlát, jelentkezz be újra!';
                        break;
                    case self::TOKEN_INVALID:
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

    private function validateToken($request): int
    {
        $token = $request->headers->all()['authorization'][0] ?? null;
        if (empty($token)) {
            return self::TOKEN_NOT_EXIST;
        }

        if (preg_match(self::HEADER_TOKEN_PATTERN, $token, $matches)) {
            $token = $matches[1];
        }

        try {
            $this->JWTAdapter->validate($token);
            return self::TOKEN_VALID;
        } catch (ExpiredException $expiredException) {
            return self::TOKEN_EXPIRED;
        } catch (\Throwable $e) {
            return self::TOKEN_VALID;
        }

        return 0;
    }


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
