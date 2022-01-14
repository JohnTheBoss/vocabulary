<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\Game;

class GameRepositoryAdapter extends RepositoryAdapter
{
    public function getEntity(): string
    {
        return Game::class;
    }
}