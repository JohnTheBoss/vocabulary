<?php

namespace App\Service;

use App\RequestModel\RequestModelInterface;
use App\ResponseModel\ResponseModelInterface;

abstract class AbstractRequestService
{
    protected RequestModelInterface $requestModel;

    protected ResponseModelInterface $responseModel;

    protected function checkRequestIsValid(): bool
    {
        $validator = $this->requestModel->getValidator();
        $validator->validate();

        if (!$validator->isValid()) {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseErrors(
                [
                    'validator' => $validator->getErrors()
                ]
            );
            return false;
        }

        return true;
    }

    abstract public function getResponseModel(): ResponseModelInterface;
}
