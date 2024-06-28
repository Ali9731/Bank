<?php

namespace App\Services\Transaction;

use App\Repositories\Card\CardRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\Transaction\Exceptions\TransactionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected CardRepositoryInterface $cardRepository
    ) {}

    public function createTransaction(string $from, string $to, int $amount)
    {
        $source = $this->cardRepository->findByColumnWith('card_number', $from, ['account']);
        $destination = $this->cardRepository->findByColumnWith('card_number', $to, ['account']);
        if (! $source || ! $destination) {
            throw new TransactionException($source ? $from : $to, TransactionException::CARD_NOT_FOUND_CODE);
        }
        if ($source->account->id == $destination->account->id) {
            throw new TransactionException('impossible transaction', TransactionException::IMPOSSIBLE_TRANSACTION_CODE);
        }
        //Todo : if not lucked ,lock account for transaction to avoid race condition.
        //if we lock the account , we need to get account data after lock
        //$from->load('account');
        //$to->load('account');
        $commission = $this->transactionRepository->settings()->commission;
        $total = $amount + $commission;
        if ($source->account->amount < $total) {
            throw new TransactionException('insufficient balance', TransactionException::INSUFFICIENT_BALANCE_CODE);
        }

        DB::beginTransaction();
        $source->account->amount -= $total;
        $destination->account->amount += $amount;
        try {
            if ($source->account->save() && $destination->account->save()) {

                $transaction = $this->transactionRepository->create([
                    'from_id' => $source->id,
                    'to_id' => $destination->id,
                    'amount' => $amount,
                ]);
                $transaction->commission()->create([
                    'amount' => $commission,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('transaction failed : '.$e->getMessage(), [
                'source' => $source->id,
                'destination' => $destination->id,
                'amount' => $amount,
            ]);
            throw new TransactionException('transaction failed', TransactionException::TRANSACTION_FAILED_CODE);
        }
        if (! empty($transaction)) {
            DB::commit();

            return $transaction;

        } else {
            DB::rollBack();
            Log::error('transaction failed : '.'transaction not saved', [
                'source' => $source->id,
                'destination' => $destination->id,
                'amount' => $amount,
            ]);
            throw new TransactionException('transaction failed', TransactionException::TRANSACTION_FAILED_CODE);
        }
    }

    public function topTransactionUserIds()
    {
        return $this->transactionRepository->topUsersIds();
    }
}
