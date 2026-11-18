<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'user_id',
        'field_id',
        'reservation_date',
        'start_time',
        'end_time',
        'duration_hours',
        'total_price',
        'status',
        'booking_code',
        'customer_name',
        'customer_phone',
        'notes',
        'cancellation_reason',
        'cancelled_at',
    ];

    /**
     * Get human-readable booking code
     */
    public function getCodeAttribute(): string
    {
        if (! empty($this->booking_code)) {
            return $this->booking_code;
        }

        return 'RSV-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    protected $casts = [
        'reservation_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'total_price' => 'float',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user that made the reservation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the field being reserved
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get the transaction associated with this reservation
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Calculate duration in hours
     */
    public function calculateDuration(): int
    {
        $startDate = $this->normalizeTimeValue($this->start_time);
        $endDate = $this->normalizeTimeValue($this->end_time);

        if (! $startDate || ! $endDate) {
            return 0;
        }

        if ($endDate->lessThan($startDate)) {
            $endDate = $endDate->copy()->addDay();
        }

        $minutes = $startDate->diffInMinutes($endDate, false);

        return (int) max(0, round($minutes / 60));
    }

    protected function normalizeTimeValue($value): ?Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_string($value)) {
            try {
                // Try a flexible parse first
                return Carbon::parse($value);
            } catch (\Exception $e) {
                // Fallback to strict formats
                foreach (['H:i:s', 'H:i'] as $format) {
                    try {
                        $parsed = Carbon::createFromFormat($format, $value);
                        if ($parsed instanceof Carbon) {
                            return $parsed;
                        }
                    } catch (\Exception $e) {
                        // continue
                    }
                }
            }
        }

        return null;
    }

    /**
     * Calculate total price based on duration and field price
     */
    public function calculateTotalPrice(): float
    {
        $duration = $this->duration_hours ?? $this->calculateDuration();
        return $duration * $this->field->price_per_hour;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('start_time') || $model->isDirty('end_time')) {
                $model->duration_hours = $model->calculateDuration();
                $model->total_price = $model->calculateTotalPrice();
            }
        });
    }
}
