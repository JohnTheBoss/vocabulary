<?php

namespace App\Service\Play;

use App\Adapter\RepositoryAdapter\DictionaryEnrolRepositoryAdapter;
use App\Adapter\RepositoryAdapter\DictionaryRepositoryAdapter;
use App\Adapter\RepositoryAdapter\GameRepositoryAdapter;
use App\Adapter\RepositoryAdapter\PuzzleRepositoryAdapter;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\Dictionary;
use App\Entity\Game;
use App\Entity\Puzzle;
use App\Entity\User;
use App\ResponseModel\Play\CreatedGameResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;
use App\Service\Authentication\TokenDecoderService;

class CreateGameService extends AbstractRequestService
{

    private $dictionaryId = null;
    private UserRepositoryAdapter $userRepository;
    private GameRepositoryAdapter $gameRepository;
    private PuzzleRepositoryAdapter $puzzleRepository;
    private TokenDecoderService $tokenDecoderService;
    private DictionaryRepositoryAdapter $dictionaryRepository;
    private DictionaryEnrolRepositoryAdapter $dictionaryEnrol;
    /**
     * @var User
     */
    private $user;

    public function __construct(
        CreatedGameResponseModel         $responseModel,
        UserRepositoryAdapter            $userRepositoryAdapter,
        DictionaryRepositoryAdapter      $dictionaryRepositoryAdapter,
        GameRepositoryAdapter            $gameRepositoryAdapter,
        PuzzleRepositoryAdapter          $puzzleRepositoryAdapter,
        DictionaryEnrolRepositoryAdapter $dictionaryEnrolRepositoryAdapter,
        TokenDecoderService              $tokenDecoderService
    )
    {
        $this->responseModel = $responseModel;
        $this->userRepository = $userRepositoryAdapter;
        $this->dictionaryRepository = $dictionaryRepositoryAdapter;
        $this->gameRepository = $gameRepositoryAdapter;
        $this->puzzleRepository = $puzzleRepositoryAdapter;
        $this->dictionaryEnrol = $dictionaryEnrolRepositoryAdapter;
        $this->tokenDecoderService = $tokenDecoderService;

        $this->user = $this->userRepository->findById($this->tokenDecoderService->getTokenData()->id);
    }

    public function setDictionaryId(int $dictionaryId)
    {
        $this->dictionaryId = $dictionaryId;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->dictionaryId === null) {
            throw new \Exception('Doesn\'t set dictionary ID. Use setDictionaryId() to set ID.');
        }

        if (!$this->checkDictionaryIsExistAndEnrolled()) {
            return $this->responseModel;
        }

        $this->createGame();

        return $this->responseModel;
    }

    private function checkDictionaryIsExistAndEnrolled()
    {
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);

        if (!isset($dictionary)) {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseStatusCode(404);
            $this->responseModel->setResponseErrors(['Az alábbi szótár nem található!']);
            return false;
        }

        $userEnrol = $this->dictionaryEnrol->findOneBy(['dictionary' => $dictionary, 'user' => $this->user]);
        if (!$userEnrol) {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseStatusCode(400);
            $this->responseModel->setResponseErrors(['Nem vetted fel az alábbi szótárt!']);
            return false;
        }

        return true;
    }

    private function createGame()
    {
        $user = $this->userRepository->findById($this->tokenDecoderService->getTokenData()->id);

        $game = $this->gameRepository->getEntity();
        /** @var Game $game */
        $game = new $game();
        $game->setUser($user);

        $this->gameRepository->save($game);

        $puzzles = $this->generatePuzzle($game);
        foreach ($puzzles as $puzzle) {
            $game->addPuzzle($puzzle);
        }
        $this->gameRepository->save($game);

        $this->responseModel->setGame($game);
    }

    private function generatePuzzle(Game $game)
    {
        /** @var Dictionary $dictionary */
        $dictionary = $this->dictionaryRepository->findById($this->dictionaryId);
        $allWords = $dictionary->getWords();

        $wordCount = count($allWords);
        $words = $allWords->toArray();

        $randomNumbers = 10;
        if ($wordCount < 10) {
            if ($wordCount > 2) {
                $randomNumbers = $wordCount - 1;
            } else {
                $randomNumbers = 1;
            }
        }

        $randomWordsIndex = array_rand($words, $randomNumbers);

        $puzzle = $this->puzzleRepository->getEntity();

        $puzzles = [];
        foreach ($randomWordsIndex as $wordIndex) {
            /** @var Puzzle $puzzle */
            $puzzle = new $puzzle();

            $puzzle->setWord($allWords[$wordIndex]);
            $type = rand(0, 1) === 0 ? 'foreign' : 'known';
            $puzzle->setType($type);
            $puzzle->setGame($game);
            $this->puzzleRepository->save($puzzle);

            $puzzles[] = $puzzle;
        }

        return $puzzles;
    }
}