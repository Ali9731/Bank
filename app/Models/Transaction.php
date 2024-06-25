<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'from_id',
        'to_id',
        'amount',
        'tracking_code',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tracking_code = Str::uuid();
        });
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    public function source()
    {
        return $this->belongsTo(Card::class, 'from_id');
    }

    public function destination()
    {
        return $this->belongsTo(Card::class, 'to_id');
    }
}
