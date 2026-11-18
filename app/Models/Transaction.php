<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PaymentAttempt;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'reservation_id',
        'transaction_code',
        'amount',
        'payment_method',
        'status',
        'payment_proof',
        'payment_proof_uploaded_at',
        'is_deposit',
        'deposit_amount',
        'description',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'payment_proof_uploaded_at' => 'datetime',
        'is_deposit' => 'boolean',
        'deposit_amount' => 'decimal:2',
    ];

    /**
     * Get the user that made the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reservation associated with this transaction
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Payment attempts related to this transaction
     */
    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode(): string
    {
        do {
            $code = 'TRX' . date('YmdHis') . rand(1000, 9999);
        } while (self::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->transaction_code) {
                $model->transaction_code = self::generateTransactionCode();
            }
        });
    }
}
