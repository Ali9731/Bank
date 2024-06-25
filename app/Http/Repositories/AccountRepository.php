<?php

namespace App\Http\Repositories;

use App\Models\Card;
use App\Models\TransactionSetting;
use App\Repositories\RepositoryInterface;

class AccountRepository implements RepositoryInterface
{
    public function all() {}

    public function create(array $data) {}

    public function update(array $data, $id) {}

    public function delete($id) {}

    public function find($id)
    {
        return Card::query()->findOrFail($id);
    }

    public function latest()
    {
        return TransactionSetting::query()->latest('updated_at')->first();
    }
}
