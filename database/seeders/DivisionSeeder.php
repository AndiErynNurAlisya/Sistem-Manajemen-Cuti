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
                'name' => 'Tour Operations',
                'description' => 'Tim operasional perjalanan ',
                'established_date' => '2020-01-15',
            ],
            [
                'name' => 'Marketing & Sales',
                'description' => 'Tim pemasaran dan penjualan',
                'established_date' => '2020-02-01',
            ],
            [
                'name' => 'Finance & Administration',
                'description' => 'Tim keuangan dan administrasi perusahaan',
                'established_date' => '2020-01-10',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}