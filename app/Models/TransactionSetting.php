<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission',
        'min_transaction',
        'max_transaction',
    ];
}
