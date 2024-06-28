<?php

namespace App\Repositories\Card;

interface CardRepositoryInterface
{
    public function all();

    public function find($id);

    public function findByColumnWith($column, $value, $relations = []);
}
