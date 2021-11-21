<?php

namespace App\Adapter;

interface RequestAdapterInterface
{
    public function getRequestData();

    public function getJsonRequestData();

    public function getQueryData();

    public function getParameters();

    public function getFiles();

    public function getHeaders();
}
