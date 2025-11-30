<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom setelah 'password'
            $table->string('full_name')->after('password');
            $table->string('phone', 20)->nullable()->after('full_name');
            $table->text('address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('address');
            
            // Role & Division
            $table->enum('role', ['admin', 'employee', 'leader', 'hrd'])
                  ->default('employee')
                  ->after('profile_photo');
            $table->foreignId('division_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('divisions')
                  ->onDelete('set null');
            
            // Status & Info
            $table->boolean('is_active')->default(true)->after('division_id');
            $table->date('join_date')->default(now())->after('is_active');
            
            $table->index('role');
            $table->index('division_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropIndex(['role']);
            $table->dropIndex(['division_id']);
            $table->dropIndex(['is_active']);
            
            $table->dropColumn([
                'full_name',
                'phone',
                'address',
                'profile_photo',
                'role',
                'division_id',
                'is_active',
                'join_date'
            ]);
        });
    }
};