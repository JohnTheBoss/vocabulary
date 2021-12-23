<?php

namespace App\ResponseModel;

abstract class AbstractResponseModel implements ResponseModelInterface
{
    protected StatusResponse $statusResponse;

    protected int $statusCode = 200;

    public function __construct()
    {
        $this->statusResponse = new StatusResponse();
    }

    abstract protected function responseData(): array;

    public function setResponseStatus(bool $status)
    {
        $this->statusResponse->success = $status;
    }

    public function setResponseStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setResponseErrors(array $errors)
    {
        $this->statusResponse->errors = $errors;
    }

    public function getResponse(): array
    {
        return array_merge(
            [
                'success' => $this->statusResponse->success,
                'errors' => $this->statusResponse->errors,
            ],
            $this->responseData()
        );
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
