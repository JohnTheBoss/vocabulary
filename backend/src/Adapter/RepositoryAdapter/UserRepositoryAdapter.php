<?php

namespace App\Adapter\RepositoryAdapter;

use App\Entity\User;

class UserRepositoryAdapter extends RepositoryAdapter
{

    public function getEntity(): string
    {
        return User::class;
    }
}
