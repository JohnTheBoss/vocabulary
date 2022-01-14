<?php

namespace App\Controller\API\Play;

use App\Controller\API\AbstractAuthenticationBaseController;
use App\Service\Play\AnswerPuzzleService;
use App\Service\Play\CreateGameService;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractAuthenticationBaseController
{
    /**
     * @Route(
     *     "/play/{directoryId}",
     *     name="play_start"
     * )
     */
    public function play($directoryId, CreateGameService $createGameService)
    {
        $createGameService->setDictionaryId($directoryId);

        $responseModel = $createGameService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }

    /**
     * @Route(
     *     "/play/answer/{puzzleId}",
     *     name="play_puzzle_answer"
     * )
     */
    public function puzzleAnswer($puzzleId, AnswerPuzzleService $answerPuzzleService)
    {
        $answerPuzzleService->setPuzzleId($puzzleId);

        $responseModel = $answerPuzzleService->getResponseModel();

        return $this->json(
            $responseModel->getResponse(),
            $responseModel->getStatusCode()
        );
    }
}