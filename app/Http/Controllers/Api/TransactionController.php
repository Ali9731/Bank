<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CardRepository;
use App\Http\Repositories\CommissionRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\TransactionRequest;
use App\Repositories\TransactionSettingRepository;
use App\Services\Sms\SmsService;
use App\Services\Sms\Strategies\KavehNegar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private $transactionSettings;

    private $transactionRepository;

    private $cardsRepository;

    private $commissionRepository;

    private $userRepository;

    private $smsService;

    public function __construct()
    {
        $this->transactionSettings = (new TransactionSettingRepository())->latest();
        $this->transactionRepository = new TransactionRepository();
        $this->cardsRepository = new CardRepository();
        $this->commissionRepository = new CommissionRepository();
        $this->userRepository = new UserRepository();
        $this->smsService = new SmsService(new KavehNegar());
    }

    public function transaction(TransactionRequest $request)
    {
        $data = $request->validated();
        $from = $this->cardsRepository->findByColumnWith('card_number', $data['from_card'], ['account']);
        $to = $this->cardsRepository->findByColumnWith('card_number', $data['to_card'], ['account']);
        if (! $from || ! $to) {
            return response([
                'error' => [
                    'message' => __('messages.card_not_found', ['card' => ! $from ? $data['from_card'] : $data['to_card']]),
                ],
            ], Response::HTTP_NOT_FOUND);
        }
        if ($from->account->id == $to->account->id) {
            return response([
                'error' => [
                    'message' => __('messages.impossible_transaction'),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //Todo : if not lucked ,lock account for transaction to avoid race condition.
        //if we lock the account , we need to get account data after lock
        //$from->load('account');
        //$to->load('account');
        $commission = $this->transactionSettings->commission;
        $total = $data['amount'] + $commission;
        if ($from->account->amount < $total) {
            return response([
                'error' => [
                    'message' => __('messages.insufficient_balance'),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        $from->account->amount -= $total;
        $to->account->amount += $data['amount'];
        try {
            if ($from->account->save() && $to->account->save()) {

                $transaction = $this->transactionRepository->create([
                    'from_id' => $from->id,
                    'to_id' => $to->id,
                    'amount' => $data['amount'],
                ]);
                $transaction->commission()->create([
                    'amount' => $commission,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('transaction failed : '.$e->getMessage(), [
                'source' => $from->id,
                'destination' => $to->id,
                'amount' => $data['amount'],
            ]);

            return response([
                'error' => [
                    'message' => __('messages.transaction_failed'),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (! empty($transaction)) {
            DB::commit();

            $this->smsService->send($from->user, __('messages.successful_transaction', ['code' => $transaction->tracking_code, 'amount' => $transaction->amount]));
            $this->smsService->send($to->user, __('messages.transaction_income', ['card' => $from->card_number, 'amount' => $transaction->amount]));

            return response([
                'success' => [
                    'message' => __('messages.transaction_successful', ['code' => $transaction->tracking_code]),
                ],
            ], Response::HTTP_OK);
        } else {
            DB::rollBack();

            return response([
                'error' => [
                    'message' => __('messages.transaction_failed'),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function topUsers()
    {
        $userIds = $this->transactionRepository->topUsersIds();

        return response()->json(['users' => $this->userRepository->getByIdsAndNTransactions($userIds, 10)]);
    }
}
