<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsService;
use App\Services\Transaction\Exceptions\TransactionException;
use App\Services\Transaction\TransactionService;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService,
        protected SmsService $smsService,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function transaction(TransactionRequest $request)
    {
        $data = $request->validated();
        try {

            $transaction = $this->transactionService->createTransaction($data['from_card'], $data['to_card'], $data['amount']);

        } catch (TransactionException $e) {
            switch ($e->getCode()) {
                case TransactionException::CARD_NOT_FOUND_CODE:
                    $errorMessage = __('messages.card_not_found', ['card' => $e->getMessage()]);
                    $errorCode = Response::HTTP_NOT_FOUND;
                    break;
                case TransactionException::IMPOSSIBLE_TRANSACTION_CODE:
                    $errorMessage = __('messages.impossible_transaction');
                    $errorCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                    break;
                case TransactionException::INSUFFICIENT_BALANCE_CODE:
                    $errorMessage = __('messages.insufficient_balance');
                    $errorCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                    break;
                default:
                    $errorMessage = __('messages.transaction_failed');
                    $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            return response([
                'error' => [
                    'message' => $errorMessage,
                ],
            ], $errorCode);
        }

        $this->smsService->send($transaction->source->user, __('messages.successful_transaction', ['code' => $transaction->tracking_code, 'amount' => $transaction->amount]));
        $this->smsService->send($transaction->destination->user, __('messages.transaction_income', ['card' => $transaction->source->card_number, 'amount' => $transaction->amount]));

        return response([
            'success' => [
                'message' => __('messages.transaction_successful', ['code' => $transaction->tracking_code]),
            ],
        ], Response::HTTP_OK);

    }

    public function topUsers()
    {
        $userIds = $this->transactionService->topTransactionUserIds();

        return response()->json(UserResource::collection($this->userRepository->getByIdsAndNTransactions($userIds, 10)));
    }
}
