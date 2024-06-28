<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function all();

    public function getByIdsAndNTransactions($ids, $count = 10);
}
