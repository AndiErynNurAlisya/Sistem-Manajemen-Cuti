<?php

namespace App\Services;

use App\Models\User;
use App\Models\LeaveQuota;
use Illuminate\Support\Facades\Log;

class LeaveQuotaService
{
    /**
     * Inisialisasi kuota untuk user baru atau tahun baru
     * 
     * @param User $user
     * @param int|null $year
     * @return LeaveQuota
     */
    public function initialize(User $user, ?int $year = null): LeaveQuota
    {
        $year = $year ?? now()->year;
        
        return LeaveQuota::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $year
            ],
            [
                'total_quota' => 12,
                'used_quota' => 0,
                'remaining_quota' => 12
            ]
        );
    }
    
    /**
     * Get kuota user untuk tahun tertentu
     * 
     * @param User $user
     * @param int|null $year
     * @return LeaveQuota
     */
    public function getQuota(User $user, ?int $year = null): LeaveQuota
    {
        $year = $year ?? now()->year;
        return $this->initialize($user, $year);
    }
    
    /**
     * Cek apakah kuota mencukupi
     * 
     * @param User $user
     * @param int $days
     * @param int|null $year
     * @return bool
     */
    public function isAvailable(User $user, int $days, ?int $year = null): bool
    {
        $quota = $this->getQuota($user, $year);
        return $quota->remaining_quota >= $days;
    }
    
    /**
     * Kurangi kuota (saat pengajuan cuti tahunan)
     * 
     * @param User $user
     * @param int $days
     * @param int|null $year
     * @return void
     * @throws \Exception
     */
    public function deduct(User $user, int $days, ?int $year = null): void
    {
        $quota = $this->getQuota($user, $year);
        
        if (!$quota->isSufficient($days)) {
            throw new \Exception("Kuota tidak mencukupi. Sisa: {$quota->remaining_quota} hari, dibutuhkan: {$days} hari.");
        }
        
        $quota->increment('used_quota', $days);
        $quota->decrement('remaining_quota', $days);
        
        Log::info("Kuota dikurangi", [
            'user_id' => $user->id,
            'days' => $days,
            'remaining' => $quota->fresh()->remaining_quota
        ]);
    }
    
    /**
     * Kembalikan kuota (saat reject/cancel cuti tahunan)
     * 
     * @param User $user
     * @param int $days
     * @param int|null $year
     * @return void
     */
    public function restore(User $user, int $days, ?int $year = null): void
    {
        $quota = $this->getQuota($user, $year);
        
        $quota->decrement('used_quota', $days);
        $quota->increment('remaining_quota', $days);
        
        // Pastikan tidak melebihi total quota
        $quota->refresh();
        if ($quota->remaining_quota > $quota->total_quota) {
            $quota->update([
                'used_quota' => 0,
                'remaining_quota' => $quota->total_quota
            ]);
        }
        
        Log::info("Kuota dikembalikan", [
            'user_id' => $user->id,
            'days' => $days,
            'remaining' => $quota->fresh()->remaining_quota
        ]);
    }
    
    /**
     * Get summary kuota user
     * 
     * @param User $user
     * @param int|null $year
     * @return array
     */
    public function getSummary(User $user, ?int $year = null): array
    {
        $quota = $this->getQuota($user, $year);
        
        return [
            'total' => $quota->total_quota,
            'used' => $quota->used_quota,
            'remaining' => $quota->remaining_quota,
            'percentage_used' => $quota->percentage_used,
            'is_exhausted' => $quota->isExhausted(),
        ];
    }
    
    /**
     * Reset kuota tahunan untuk semua user (dijalankan awal tahun via cron)
     * 
     * @param int|null $year
     * @return int
     */
    public function resetAnnualQuota(?int $year = null): int
    {
        $year = $year ?? now()->year;
        $count = 0;
        
        // Get all employee & leader
        $users = User::whereIn('role', ['employee', 'leader'])
                     ->where('is_active', true)
                     ->get();
        
        foreach ($users as $user) {
            $this->initialize($user, $year);
            $count++;
        }
        
        Log::info("Kuota tahunan direset untuk {$count} user", ['year' => $year]);
        
        return $count;
    }
    
    /**
     * Adjust quota untuk user tertentu (misal: tambah/kurangi kuota)
     * 
     * @param User $user
     * @param int $newTotal
     * @param int|null $year
     * @return void
     */
    public function adjustQuota(User $user, int $newTotal, ?int $year = null): void
    {
        $quota = $this->getQuota($user, $year);
        
        $difference = $newTotal - $quota->total_quota;
        
        $quota->update([
            'total_quota' => $newTotal,
            'remaining_quota' => $quota->remaining_quota + $difference
        ]);
        
        Log::info("Kuota disesuaikan", [
            'user_id' => $user->id,
            'old_total' => $quota->total_quota,
            'new_total' => $newTotal,
            'remaining' => $quota->fresh()->remaining_quota
        ]);
    }
}