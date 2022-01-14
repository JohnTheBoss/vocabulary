<?php

namespace App\Service\Play;

use App\Adapter\RepositoryAdapter\PuzzleRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\Puzzle;
use App\Entity\User;
use App\RequestModel\Play\PuzzleAnswerRequestModel;
use App\ResponseModel\Play\PuzzleAnswerResponseModel;
use App\ResponseModel\PuzzleAnswerResponse;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class AnswerPuzzleService extends AbstractRequestService
{

    private int $puzzleId;
    private UserRepositoryAdapter $userRepository;
    private PuzzleRepositoryAdapter $puzzleRepository;
    private TokenDecoderService $tokenDecoderService;
    private User $user;
    private PuzzleAnswerRequestModel $puzzleAnswerRequestModel;
    private PuzzleAnswerResponse $puzzleAnswerResponse;

    public function __construct(
        PuzzleAnswerRequestModel  $puzzleAnswerRequestModel,
        PuzzleRepositoryAdapter   $puzzleRepositoryAdapter,
        UserRepositoryAdapter     $userRepositoryAdapter,
        TokenDecoderService       $tokenDecoderService,
        PuzzleAnswerResponse      $puzzleAnswerResponse,
        PuzzleAnswerResponseModel $responseModel
    )
    {
        $this->responseModel = $responseModel;
        $this->userRepository = $userRepositoryAdapter;
        $this->puzzleRepository = $puzzleRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;
        $this->puzzleAnswerRequestModel = $puzzleAnswerRequestModel;
        $this->puzzleAnswerResponse = $puzzleAnswerResponse;

        $this->user = $this->userRepository->findById($this->tokenDecoderService->getTokenData()->id);
    }

    public function getResponseModel(): ResponseModelInterface
    {
        $this->answerPuzzle();

        return $this->responseModel;
    }

    public function setPuzzleId($puzzle)
    {
        $this->puzzleId = $puzzle;
    }

    private function answerPuzzle()
    {
        /** @var Puzzle $puzzle */
        $puzzle = $this->puzzleRepository->findById($this->puzzleId);

        if (!isset($puzzle)) {
            $this->responseModel->setResponseErrors(['Nincs ilyen azonosítújú kérdés!']);
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseStatus(false);
        }

        if ($puzzle->getGame()->getUser()->getId() !== $this->user->getId()) {
            $this->responseModel->setResponseErrors(['Az alábbi kérdés nem hozzád tartozik!']);
            $this->responseModel->setResponseStatusCode(401);
            $this->responseModel->setResponseStatus(false);
            return;
        }

        if (!empty($puzzle->getAnswer())) {
            $this->responseModel->setResponseErrors(['Az alábbi kérdésre korábban már válaszoltál a válaszod nem kerül felülírásra!']);
            $this->responseModel->setResponseStatusCode(200);
        } else {
            $puzzle->setAnswer($this->puzzleAnswerRequestModel->answer);
            $this->puzzleRepository->save($puzzle);
        }

        $this->puzzleAnswerResponse->setPuzzle($puzzle);

        $this->responseModel->setResponseStatus(true);
        $this->responseModel->setResponseStatusCode(201);
        $this->responseModel->setPuzzleAnswerResponse($this->puzzleAnswerResponse);
    }
}