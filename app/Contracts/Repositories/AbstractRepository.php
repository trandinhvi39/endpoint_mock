<?php

namespace App\Contracts\Repositories;

interface AbstractRepository
{
    public function model();

    public function setGuard();
}
