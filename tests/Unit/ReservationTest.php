<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    public function test_calculate_duration_accepts_hour_minute_format(): void
    {
        $reservation = new Reservation([
            'start_time' => '02:44',
            'end_time' => '03:45',
        ]);

        $this->assertSame(1, $reservation->calculateDuration());
    }
}
