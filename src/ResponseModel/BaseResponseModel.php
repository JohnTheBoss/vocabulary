<?php

namespace App\ResponseModel;

class BaseResponseModel extends AbstractResponseModel implements ResponseModelInterface
{

    protected function responseData(): array
    {
        return [];
    }
}
