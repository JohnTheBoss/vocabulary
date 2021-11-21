<?php

namespace App\RequestModel;

abstract class AbstractRequest implements RequestModelInterface
{
    protected function getData($data, $index)
    {
        if (isset($data[$index])) {
            return $data[$index];
        }
        return null;
    }
}
