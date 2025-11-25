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
        
        // // 2. HRD
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
        
        // Get divisions
        $tourOps = Division::where('name', 'Tour Operations')->first();
        $marketing = Division::where('name', 'Marketing & Sales')->first();
        $finance = Division::where('name', 'Finance & Administration')->first();
        
        // 3. LEADERS (Ketua Divisi)
        $leader1 = User::create([
            'name' => 'leader1',
            'email' => 'ahmad.rizki@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Ahmad Rizki',
            'phone' => '081234567892',
            'address' => 'Bekasi',
            'role' => 'leader',
            'division_id' => $tourOps->id,
            'is_active' => true,
            'join_date' => '2020-02-01',
            'email_verified_at' => now(),
        ]);
        
        $leader2 = User::create([
            'name' => 'leader2',
            'email' => 'budi.santoso@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Budi Santoso',
            'phone' => '081234567893',
            'address' => 'Tangerang',
            'role' => 'leader',
            'division_id' => $marketing->id,
            'is_active' => true,
            'join_date' => '2020-02-15',
            'email_verified_at' => now(),
        ]);
        
        $leader3 = User::create([
            'name' => 'leader3',
            'email' => 'dewi.lestari@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Dewi Lestari',
            'phone' => '081234567894',
            'address' => 'Depok',
            'role' => 'leader',
            'division_id' => $finance->id,
            'is_active' => true,
            'join_date' => '2020-03-01',
            'email_verified_at' => now(),
        ]);
        
        // Update divisions dengan leader_id
        $tourOps->update(['leader_id' => $leader1->id]);
        $marketing->update(['leader_id' => $leader2->id]);
        $finance->update(['leader_id' => $leader3->id]);
        
        // 4. EMPLOYEES (Karyawan)
        // Tour Operations Team
        User::create([
            'name' => 'employee1',
            'email' => 'john.doe@umrahtravel.com',
            'password' => $password,
            'full_name' => 'John Doe',
            'phone' => '081234567895',
            'address' => 'Jakarta Timur',
            'role' => 'employee',
            'division_id' => $tourOps->id,
            'is_active' => true,
            'join_date' => '2021-06-01',
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'employee2',
            'email' => 'jane.smith@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Jane Smith',
            'phone' => '081234567896',
            'address' => 'Jakarta Barat',
            'role' => 'employee',
            'division_id' => $tourOps->id,
            'is_active' => true,
            'join_date' => '2021-07-15',
            'email_verified_at' => now(),
        ]);
        
        // Marketing Team
        User::create([
            'name' => 'employee3',
            'email' => 'andi.wijaya@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Andi Wijaya',
            'phone' => '081234567897',
            'address' => 'Bogor',
            'role' => 'employee',
            'division_id' => $marketing->id,
            'is_active' => true,
            'join_date' => '2022-01-10',
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'employee4',
            'email' => 'sari.melati@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Sari Melati',
            'phone' => '081234567898',
            'address' => 'Jakarta Utara',
            'role' => 'employee',
            'division_id' => $marketing->id,
            'is_active' => true,
            'join_date' => '2022-03-20',
            'email_verified_at' => now(),
        ]);
        
        // Finance Team
        User::create([
            'name' => 'employee5',
            'email' => 'rudi.hartono@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Rudi Hartono',
            'phone' => '081234567899',
            'address' => 'Tangerang Selatan',
            'role' => 'employee',
            'division_id' => $finance->id,
            'is_active' => true,
            'join_date' => '2021-09-01',
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'employee6',
            'email' => 'linda.permata@umrahtravel.com',
            'password' => $password,
            'full_name' => 'Linda Permata',
            'phone' => '081234567800',
            'address' => 'Bekasi Timur',
            'role' => 'employee',
            'division_id' => $finance->id,
            'is_active' => true,
            'join_date' => '2022-05-15',
            'email_verified_at' => now(),
        ]);
    }
}