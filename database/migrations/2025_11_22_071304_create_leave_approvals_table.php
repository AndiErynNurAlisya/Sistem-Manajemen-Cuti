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
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')
                  ->constrained('leave_requests')
                  ->onDelete('cascade');
            $table->foreignId('approver_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            $table->enum('approver_role', ['leader', 'hrd']);
            $table->enum('status', ['approved', 'rejected']);
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->useCurrent();
            
            $table->timestamps();
            
            // Indexes
            $table->index('leave_request_id');
            $table->index('approver_id');
            $table->index('approver_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};