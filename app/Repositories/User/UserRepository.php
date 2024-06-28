<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::query()->with('card')->get();
    }

    public function getByIdsAndNTransactions($ids, $count = 10)
    {
        return User::query()->whereIn('id', $ids)->with(['transactions' => function ($query) use ($count) {
            $query->orderBY('id', 'desc')->take($count);
        }])->get();
    }
}
