<?php

namespace App\Repositories\Card;

use App\Models\Card;

class CardRepository implements CardRepositoryInterface
{
    public function all()
    {
        return Card::query()->with('account')->get();
    }

    public function find($id)
    {
        return Card::query()->findOrFail($id);
    }

    public function findByColumnWith($column, $value, $relations = [])
    {
        return Card::query()->where($column, $value)->with($relations)->first();
    }
}
