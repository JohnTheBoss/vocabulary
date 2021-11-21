<?php

namespace App\Adapter;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestAdapter implements RequestAdapterInterface
{
    protected $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function getParameters()
    {
        return $this->request->attributes->all();
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


    public function getHeaders()
    {
        return $this->request->headers->all();
    }
}
