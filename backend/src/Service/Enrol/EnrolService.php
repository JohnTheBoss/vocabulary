<?php

namespace App\Service\Enrol;

use App\Adapter\RepositoryAdapter\DictionaryEnrolRepositoryAdapter;
use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\DictionaryEnrol;
use App\Repository\DictionaryEnrolRepository;
use App\ResponseModel\BaseResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class EnrolService extends AbstractRequestService
{

    private int $dictionaryId;
    private DictionaryRepositoryAdapter $dictionaryRepository;
    private DictionaryEnrolRepositoryAdapter $dictionaryEnrolRepository;
    private TokenDecoderService $tokenDecoderService;
    private UserRepositoryAdapter $userRepository;

    public function __construct(
        BaseResponseModel                $responseModel,
        UserRepositoryAdapter            $userRepositoryAdapter,
        DictionaryRepositoryAdapter      $dictionaryRepository,
        DictionaryEnrolRepositoryAdapter $dictionaryEnrolRepository,
        TokenDecoderService              $tokenDecoderService
    )
    {
        $this->responseModel = $responseModel;
        $this->userRepository = $userRepositoryAdapter;
        $this->dictionaryRepository = $dictionaryRepository;
        $this->dictionaryEnrolRepository = $dictionaryEnrolRepository;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if (empty($this->dictionaryId)) {
            throw new \Exception('Dictionary ID doesn\'t setted!');
        }

        $this->enrol();

        return $this->responseModel;
    }

    public function setDictionaryId($id)
    {
        $this->dictionaryId = $id;
    }

    private function enrol()
    {
        $user = $this->tokenDecoderService->getTokenData();
        $dictionaryEntity = $this->dictionaryRepository->findById($this->dictionaryId);
        $userEntity = $this->userRepository->findById($user->id);

        if (empty($dictionaryEntity)) {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors([
                'Ilyen azonosítójú szótár nem létezik!'
            ]);
            $this->responseModel->setResponseStatusCode(404);

            return null;
        }

        $findEnrolled = $this->dictionaryEnrolRepository->findOneBy(['user' => $userEntity, 'dictionary' => $dictionaryEntity]);

        if (!empty($findEnrolled)) {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseErrors([
                'Az alábbi szótárat már felvette!'
            ]);
            $this->responseModel->setResponseStatusCode(409);

            return null;
        }

        $dictionaryEnrolEntity = $this->dictionaryEnrolRepository->getEntity();
        /** @var DictionaryEnrol $dictionaryEnrol */
        $dictionaryEnrol = new $dictionaryEnrolEntity();
        $dictionaryEnrol->setDictionary($dictionaryEntity);
        $dictionaryEnrol->setUser($userEntity);

        $this->dictionaryEnrolRepository->save($dictionaryEnrol);

        $this->responseModel->setResponseStatus(true);
        $this->responseModel->setResponseStatusCode(201);

    }
}