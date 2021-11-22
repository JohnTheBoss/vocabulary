<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DocsController extends AbstractController
{
    /**
     * @Route(
     *     "/docs",
     *     name="apidocs_index"
     * )
     */
    public function docsIndex()
    {
        return $this->getApiDocsFile('index.html');
    }

    /**
     * @Route(
     *     "/docs/swagger",
     *     name="apidocs_swagger"
     * )
     */
    public function serveSwaggerFile(){
        return $this->getApiDocsFile('swagger.yaml');
    }

    private function getApiDocsFile($path)
    {
        $filename = __DIR__ . "/../../../public/apidocs/{$path}";
        $fileContent = file_get_contents($filename);
        /*
        $file = new File($filename);

        return $this->file($file);
*/
        /*

                $fileContent = file_get_contents($filename);

                $response = new Response($fileContent);

                // Create the disposition of the file
                $disposition = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $path
                );

                // Set the content disposition
                $response->headers->set('Content-Disposition', $disposition);

                // Dispatch request
                return $response;

        */


        if (file_exists($filename)) {
            $response = new Response($fileContent);
            $fileType = mime_content_type($filename);

            $path_parts = pathinfo($filename);
            if ($path_parts['extension'] === 'js') {
                $fileType = 'application/javascript';
            }

            $response->headers->set('Content-Type', $fileType);
            return $response->send();
        } else {
            throw new NotFoundHttpException("Guide {$path} Not Found.");
        }
    }
}
