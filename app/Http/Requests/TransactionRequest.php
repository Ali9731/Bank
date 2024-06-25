<?php

namespace App\Http\Requests;

use App\Repositories\TransactionSettingRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class TransactionRequest extends FormRequest
{
    private $transactionSettings;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->transactionSettings = (new TransactionSettingRepository())->latest();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_card' => [
                'required',
                'regex:'.Config::get('app.cardNumberRegex'),
            ],
            'to_card' => [
                'required',
                'regex:'.Config::get('app.cardNumberRegex'),
            ],
            'amount' => 'required|integer|between:'.$this->transactionSettings->min_transaction.','.$this->transactionSettings->max_transaction,
        ];
    }

    public function messages(): array
    {
        return [
            'from_card.required' => __('messages.from_card_field.required'),
            'from_card.regex' => __('messages.from_card_field.regex'),
            'to_card.required' => __('messages.from_card_field.required'),
            'to_card.regex' => __('messages.from_card_field.regex'),
            'amount.required' => __('messages.amount_field.required'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('from_card') && (strlen($this->from_card) <= 32)) {
            $this->merge(['from_card' => convertToEn($this->from_card)]);
        }
        if ($this->has('to_card') && (strlen($this->to_card) <= 32)) {
            $this->merge(['to_card' => convertToEn($this->to_card)]);
        }
        if ($this->has('amount') && (strlen($this->amount) <= strlen($this->transactionSettings->max_transaction))) {
            $this->merge(['amount' => convertToEn($this->amount)]);
        }
    }
}
