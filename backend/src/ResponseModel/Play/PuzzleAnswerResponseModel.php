<?php

namespace App\ResponseModel\Play;

use App\ResponseModel\AbstractResponseModel;
use App\ResponseModel\PuzzleAnswerResponse;

class PuzzleAnswerResponseModel extends AbstractResponseModel
{

    private PuzzleAnswerResponse $puzzleAnswer;

    public function setPuzzleAnswerResponse(PuzzleAnswerResponse $puzzleAnswerResponse)
    {
        $this->puzzleAnswer = $puzzleAnswerResponse;
    }

    protected function responseData(): array
    {
        if (isset($this->puzzleAnswer)) {
            return [
                'puzzle' => [
                    'id' => $this->puzzleAnswer->getPuzzleId(),
                    'questionType' => $this->puzzleAnswer->getQuestionType(),
                    'question' => $this->puzzleAnswer->getQuestion(),
                    'yourAnswer' => $this->puzzleAnswer->getUserAnswer(),
                    'correctAnswer' => $this->puzzleAnswer->getCorrectAnswer(),
                    'correct' => $this->puzzleAnswer->userAnswerIsCorrect(),
                ],
            ];
        }
        return [];
    }
}