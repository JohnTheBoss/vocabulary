<?php

namespace App\ResponseModel;

interface ResponseModelInterface
{
    public function getResponse(): array;

    public function getStatusCode(): int;

}
