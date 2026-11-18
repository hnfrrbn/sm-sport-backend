<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'price_per_hour',
        'status',
        'description',
    ];

    /**
     * Get all reservations for this field
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all active reservations
     */
    public function activeReservations(): HasMany
    {
        return $this->hasMany(Reservation::class)
            ->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Get a representative vector image URL for this field from local assets.
     */
    public function getImageUrlAttribute(): string
    {
        $type = strtolower($this->type ?? '');
        $name = strtolower($this->name ?? '');

        if (str_contains($type, 'badminton') || str_contains($name, 'badminton')) {
            return 'images/badminton.svg';
        }

        return 'images/futsal.svg';
    }

    /**
     * Get elegant gradient background style according to field type:
     * Hijau emerald untuk Futsal, biru cyan untuk Badminton.
     */
    public function getGradientStyleAttribute(): string
    {
        $type = strtolower($this->type ?? '');
        $name = strtolower($this->name ?? '');

        if (str_contains($type, 'badminton') || str_contains($name, 'badminton')) {
            return 'linear-gradient(135deg, #0c4a6e 0%, #0284c7 50%, #06b6d4 100%)';
        }

        return 'linear-gradient(135deg, #064e3b 0%, #059669 50%, #10b981 100%)';
    }

    /**
     * Check if field is available at specific time
     */
    public function isAvailable(\DateTime $startTime, \DateTime $endTime, ?int $excludeReservationId = null): bool
    {
        $query = $this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('reservation_date', $startTime->format('Y-m-d'))
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->count() === 0;
    }
}
