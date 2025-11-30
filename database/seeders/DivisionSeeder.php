<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Barista',
                'description' => 'Tim penyedia dan peracik minuman kopi serta pelayanan cafè.',
                'established_date' => '2021-01-10',
            ],
            [
                'name' => 'Kitchen',
                'description' => 'Tim pengolahan makanan dan produksi menu utama cafè.',
                'established_date' => '2021-02-01',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
