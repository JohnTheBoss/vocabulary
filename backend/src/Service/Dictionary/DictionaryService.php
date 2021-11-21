<?php

namespace App\Service\Dictionary;

use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\Dictionary;
use App\RequestModel\Dictionary\DictionaryRequest;
use App\ResponseModel\Dictionary\DictionaryResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class DictionaryService extends AbstractRequestService
{

    private const CREATE_TYPE = 1;

    private const UPDATE_TYPE = 0;

    private $type = null;

    private $dictionaryId;

    private DictionaryRepositoryAdapter $repository;
    private TokenDecoderService $tokenDecoderService;
    private UserRepositoryAdapter $userRepository;

    public function __construct(
        DictionaryRequest           $dictionaryRequest,
        DictionaryRepositoryAdapter $dictionaryRepositoryAdapter,
        DictionaryResponseModel     $dictionaryResponseModel,
        UserRepositoryAdapter       $userRepositoryAdapter,
        TokenDecoderService         $tokenDecoderService
    )
    {
        $this->requestModel = $dictionaryRequest;
        $this->repository = $dictionaryRepositoryAdapter;
        $this->responseModel = $dictionaryResponseModel;
        $this->userRepository = $userRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
    }

    public function setToCreate()
    {
        $this->type = self::CREATE_TYPE;
    }

    public function setToUpdate()
    {
        $this->type = self::UPDATE_TYPE;
    }

    public function setId($id)
    {
        $this->type = self::UPDATE_TYPE;
        $this->dictionaryId = $id;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->type === null) {
            throw new \Exception('Doesn\'t set model type. Use setToCreate() to set create mode, setToUpdate to set update mode.');
        }

        if (!$this->checkRequestIsValid()) {
            return $this->responseModel;
        }

        if ($this->type === self::CREATE_TYPE) {
            $this->create();
        } else {
            $this->update();
        }

        return $this->responseModel;
    }

    private function create()
    {
        $user = $this->userRepository->findById($this->tokenDecoderService->getTokenData()->id);
        $newDictionary = new Dictionary();
        $newDictionary->setName($this->requestModel->name);
        $newDictionary->setKnownLanguage($this->requestModel->knownLanguage);
        $newDictionary->setForeignLanguage($this->requestModel->foreignLanguage);
        $newDictionary->setUser($user);

        $this->repository->save($newDictionary);

        $this->responseModel->setResponseStatusCode(201);
        $this->responseModel->setResponseStatus(true);
    }

    private function update()
    {

    }
}
