<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAttempt extends Model
{
    use HasFactory;

    protected $table = 'payment_attempts';

    protected $fillable = [
        'transaction_id',
        'provider',
        'method',
        'status',
        'deeplink',
        'payload',
        'attempt_token',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
