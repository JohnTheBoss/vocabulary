<?php

namespace App\ResponseModel\Play;

use App\Entity\Game;
use App\Entity\Puzzle;
use App\ResponseModel\AbstractResponseModel;

class CreatedGameResponseModel extends AbstractResponseModel
{
    private Game $game;

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    protected function responseData(): array
    {
        if (isset($this->game)) {
            return [
                "game" => [
                    'gameId' => $this->game->getId(),
                    'puzzles' => $this->getPuzzles(),
                ],
            ];
        }
        return [];
    }

    private function getPuzzles()
    {
        $puzzles = [];

        /**
         * @var Puzzle $puzzle
         */
        foreach ($this->game->getPuzzles()->toArray() as $puzzle) {
            $responsePuzzle = new \stdClass();

            $responsePuzzle->id = $puzzle->getId();
            $responsePuzzle->type = $puzzle->getType();
            if ($puzzle->getType() === 'foreign') {
                $responsePuzzle->question = $puzzle->getWord()->getForeignLanguage();
            } else {
                $responsePuzzle->question = $puzzle->getWord()->getKnownLanguage();
            }

            $puzzles[] = $responsePuzzle;
        }

        return $puzzles;
    }
}