<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password default untuk semua user: password
        $password = Hash::make('password');
        
        // 1. ADMIN (Owner/Direktur)
        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@umrahtravel.com',
        //     'password' => $password,
        //     'full_name' => 'Admin System',
        //     'phone' => '081234567890',
        //     'address' => 'Jakarta Pusat',
        //     'role' => 'admin',
        //     'division_id' => null,
        //     'is_active' => true,
        //     'join_date' => '2020-01-01',
        //     'email_verified_at' => now(),
        // ]);
        
        // 2. HRD
        // User::create([
        //     'name' => 'hrd',
        //     'email' => 'hrd@umrahtravel.com',
        //     'password' => $password,
        //     'full_name' => 'Siti Nurhaliza',
        //     'phone' => '081234567891',
        //     'address' => 'Jakarta Selatan',
        //     'role' => 'hrd',
        //     'division_id' => null,
        //     'is_active' => true,
        //     'join_date' => '2020-01-05',
        //     'email_verified_at' => now(),
        // ]);
        

        // ================================================
        //                CAFE DIVISIONS
        // ================================================

        // Ambil divisi (pastikan sudah ada di DivisionSeeder)
        $barista = Division::where('name', 'Barista')->first();
        $kitchen = Division::where('name', 'Kitchen')->first();

        // ================================================
        //                LEADERS (1 per divisi)
        // ================================================

        $leaderBarista = User::create([
            'name' => 'leader_barista',
            'email' => 'leader.barista@cafe.com',
            'password' => $password,
            'full_name' => 'Rizky Setiawan',
            'phone' => '081234500001',
            'address' => 'Jakarta Selatan',
            'role' => 'leader',
            'division_id' => $barista->id,
            'is_active' => true,
            'join_date' => '2021-01-01',
            'email_verified_at' => now(),
        ]);

        $leaderKitchen = User::create([
            'name' => 'leader_kitchen',
            'email' => 'leader.kitchen@cafe.com',
            'password' => $password,
            'full_name' => 'Siti Ramadhani',
            'phone' => '081234500002',
            'address' => 'Bogor',
            'role' => 'leader',
            'division_id' => $kitchen->id,
            'is_active' => true,
            'join_date' => '2021-01-05',
            'email_verified_at' => now(),
        ]);

        // Update divisions dengan leader_id
        $barista->update(['leader_id' => $leaderBarista->id]);
        $kitchen->update(['leader_id' => $leaderKitchen->id]);

        // ================================================
        //                EMPLOYEES (1 per divisi)
        // ================================================

        // BARISTA STAFF
        User::create([
            'name' => 'barista1',
            'email' => 'barista.staff@cafe.com',
            'password' => $password,
            'full_name' => 'Andi Barista',
            'phone' => '081234500101',
            'address' => 'Jakarta Barat',
            'role' => 'employee',
            'division_id' => $barista->id,
            'is_active' => true,
            'join_date' => '2022-01-10',
            'email_verified_at' => now(),
        ]);

        // KITCHEN STAFF
        User::create([
            'name' => 'kitchen1',
            'email' => 'kitchen.staff@cafe.com',
            'password' => $password,
            'full_name' => 'Dewi Kitchen',
            'phone' => '081234500102',
            'address' => 'Jakarta Timur',
            'role' => 'employee',
            'division_id' => $kitchen->id,
            'is_active' => true,
            'join_date' => '2022-02-05',
            'email_verified_at' => now(),
        ]);
    }
}
