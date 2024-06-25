<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    public function all()
    {
        return User::query()->with('card')->get();
    }
    public function getByIdsAndNTransactions($ids, $count = 10)
    {
        return User::query()->whereIn('id', $ids)->with(['transactions' => function ($query) use ($count) {
            $query->orderBY('id','desc')->take($count);
        }])->get();
    }
    public function create(array $data) {}

    public function update(array $data, $id) {}

    public function delete($id) {}

    public function find($id)
    {
        //
    }

    public function latest()
    {
        //
    }
}
