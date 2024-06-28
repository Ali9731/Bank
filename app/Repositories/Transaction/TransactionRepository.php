<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Models\TransactionSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
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
            ->where('t.created_at', '>', Carbon::now()->subMinutes(10))
            ->groupBy('c.user_id')
            ->orderBy('transaction_count')
            ->get()->pluck('user_id');
    }

    public function settings()
    {
        return TransactionSetting::query()->latest('updated_at')->first();
    }
}
