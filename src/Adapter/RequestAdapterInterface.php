<?php

namespace App\Adapter;

interface RequestAdapterInterface
{
    public function getRequestData();

    public function getJsonRequestData();

    public function getQueryData();

    public function getFiles();

    public function getHeaders();
}
