<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'name' => 'Futsal 1',
                'type' => 'futsal',
                'capacity' => 10,
                'price_per_hour' => 150000,
                'status' => 'available',
                'description' => 'Lapangan futsal standar dengan fasilitas lengkap',
            ],
            [
                'name' => 'Futsal 2',
                'type' => 'futsal',
                'capacity' => 10,
                'price_per_hour' => 150000,
                'status' => 'available',
                'description' => 'Lapangan futsal standar dengan fasilitas lengkap',
            ],
            [
                'name' => 'Badminton 1',
                'type' => 'badminton',
                'capacity' => 4,
                'price_per_hour' => 80000,
                'status' => 'available',
                'description' => 'Lapangan badminton dengan AC',
            ],
            [
                'name' => 'Badminton 2',
                'type' => 'badminton',
                'capacity' => 4,
                'price_per_hour' => 80000,
                'status' => 'available',
                'description' => 'Lapangan badminton dengan AC',
            ],
            [
                'name' => 'Badminton 3',
                'type' => 'badminton',
                'capacity' => 4,
                'price_per_hour' => 80000,
                'status' => 'available',
                'description' => 'Lapangan badminton dengan AC',
            ],
        ];

        foreach ($fields as $field) {
            Field::updateOrCreate(
                ['name' => $field['name']],
                $field
            );
        }
    }
}
