<?php

namespace App\Repositories;

use App\Models\TransactionSetting;

class TransactionSettingRepository implements RepositoryInterface
{
    public function all() {}

    public function create(array $data) {}

    public function update(array $data, $id) {}

    public function delete($id) {}

    public function find($id) {}

    public function latest()
    {
        return TransactionSetting::query()->latest('updated_at')->first();
    }
}
