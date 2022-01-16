<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/",name="frontend_index")
     */
    public function serveIndex()
    {
        return $this->getFile('index.html');
    }

    /**
     * @Route(
     *     "/{path}",
     *     name="frontend",
     *     requirements={"path"="^(?!api).*"}
     *     )
     */
    public function serveAny($path)
    {
        return $this->getFile($path);
    }

    private function getFile($path)
    {
        $filename = __DIR__ . "/../../../public/frontend/{$path}";

        if (file_exists($filename)) {
            $fileContent = file_get_contents($filename);
            $response = new Response($fileContent);
            $fileType = mime_content_type($filename);

            $path_parts = pathinfo($filename);
            if ($path_parts['extension'] === 'js') {
                $fileType = 'application/javascript';
            }

            $path_parts = pathinfo($filename);
            if ($path_parts['extension'] === 'css') {
                $fileType = 'text/css';
            }

            $response->headers->set('Content-Type', $fileType);
            return $response->send();
        } else {
            return $this->getFile('index.html');
        }
    }
}
