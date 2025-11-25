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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Jenis & Tanggal
            $table->enum('leave_type', ['annual', 'sick']);
            $table->date('request_date')->default(now());
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            
            // Detail Pengajuan
            $table->text('reason');
            $table->string('address_during_leave');
            $table->string('emergency_contact', 20);
            
            // File Upload
            $table->string('medical_certificate')->nullable(); // Surat dokter (cuti sakit)
            $table->string('request_letter_pdf')->nullable(); // Surat permohonan (tahunan)
            $table->string('approval_letter_pdf')->nullable(); // Surat izin (dari HRD)
            
            // Status & Notes
            $table->enum('status', [
                'pending',
                'approved_by_leader',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performance
            $table->index('user_id');
            $table->index('status');
            $table->index('leave_type');
            $table->index(['start_date', 'end_date']);
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};