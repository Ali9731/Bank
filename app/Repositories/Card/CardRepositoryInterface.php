<?php

namespace App\Repositories\Card;

interface CardRepositoryInterface
{
    public function all();

    public function findByColumnWith($column, $value, $relations = []);
}
