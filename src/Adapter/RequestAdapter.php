<?php

namespace App\Adapter;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestAdapter
{
    protected $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function getRequestData()
    {
        return $this->request->getContent();
    }

    public function getJsonRequestData()
    {
        return $this->request->toArray();
    }

    public function getQueryData()
    {
        return $this->request->query->all();
    }

    public function getFiles()
    {
        return $this->request->files->all();
    }


}
