<?php

namespace App\Repositories\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data);

    public function topUsersIds();

    public function settings();
}
