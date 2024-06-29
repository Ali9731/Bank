<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getByIdsAndNTransactions($ids, $count = 10);
}
