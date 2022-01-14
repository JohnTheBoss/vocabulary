<?php

namespace App\ResponseModel;

use App\Entity\Puzzle;

class PuzzleAnswerResponse
{
    private Puzzle $puzzle;
    private $userAnswer;
    private $correctAnswer;

    public function setPuzzle(Puzzle $puzzle)
    {
        $this->puzzle = $puzzle;

        $this->userAnswer = $this->getUserAnswer();
        $this->correctAnswer = $this->getCorrectAnswer();
    }

    public function userAnswerIsCorrect()
    {
        return strtolower($this->userAnswer) === strtolower($this->correctAnswer);
    }

    public function getPuzzleId()
    {
        return $this->puzzle->getId();
    }

    public function getQuestionType()
    {
        return $this->puzzle->getType();
    }

    public function getUserAnswer()
    {
        return $this->puzzle->getAnswer();
    }

    public function getCorrectAnswer()
    {
        if ($this->getQuestionType() !== 'foreign') {
            return $this->puzzle->getWord()->getForeignLanguage();
        }

        return $this->puzzle->getWord()->getKnownLanguage();
    }

    public function getQuestion()
    {
        if ($this->getQuestionType() === 'foreign') {
            return $this->puzzle->getWord()->getForeignLanguage();
        }

        return $this->puzzle->getWord()->getKnownLanguage();
    }
}