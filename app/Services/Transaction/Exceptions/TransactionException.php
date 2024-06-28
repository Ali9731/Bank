<?php

namespace App\Services\Transaction\Exceptions;

use Exception;

class TransactionException extends Exception
{
    const CARD_NOT_FOUND_CODE = 1;

    const IMPOSSIBLE_TRANSACTION_CODE = 2;

    const INSUFFICIENT_BALANCE_CODE = 3;

    const TRANSACTION_FAILED_CODE = 4;

    public function render()
    {
        return response()->json(['error' => true, 'message' => $this->getMessage(), 'code' => $this->code]);
    }
}
