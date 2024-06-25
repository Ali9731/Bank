<?php

namespace App\Http\Repositories;

use App\Models\Transaction;
use App\Repositories\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements RepositoryInterface
{
    public function all() {}

    public function create(array $data)
    {
        return Transaction::on('mysql')->create($data);
    }

    public function topUsersIds()
    {
        return DB::table('transactions as t')
            ->selectRaw('user_id, count(*) as transaction_count')
            ->join('cards as c', 't.from_id', '=', 'c.id')
            ->where('t.created_at', '>' , Carbon::now()->subMinutes(5000))
            ->groupBy('c.user_id')
            ->orderBy('transaction_count')
            ->get()->pluck('user_id');
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
