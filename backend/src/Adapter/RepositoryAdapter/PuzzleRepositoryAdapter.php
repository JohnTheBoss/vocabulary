<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\Puzzle;

class PuzzleRepositoryAdapter extends RepositoryAdapter
{
    public function getEntity(): string
    {
        return Puzzle::class;
    }
}