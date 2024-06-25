<?php

namespace App\Http\Repositories;

use App\Models\Commission;
use App\Repositories\RepositoryInterface;

class CommissionRepository implements RepositoryInterface
{
    public function all() {}

    public function create(array $data)
    {
        return Commission::query()->create($data);
    }

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
