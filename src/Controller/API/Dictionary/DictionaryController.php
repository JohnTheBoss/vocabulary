<?php

namespace App\Controller\API\Dictionary;

use App\Controller\API\AbstractAuthenticationBaseController;
use Symfony\Component\Routing\Annotation\Route;

class DictionaryController extends AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     name="dictionary_list",
     *     path="/dictionary",
     *     methods={"GET"}
     *     )
     */
    public function index()
    {
        return $this->json([], 201);
    }
}
