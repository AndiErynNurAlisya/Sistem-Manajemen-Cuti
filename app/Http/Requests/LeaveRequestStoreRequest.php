<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\WorkingDayCalculator;
use App\Services\LeaveQuotaService;
use App\Models\LeaveRequest;

class LeaveRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canRequestLeave();
    }

    public function rules(): array
    {
        $rules = [
            'leave_type' => ['required', 'in:annual,sick'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
            'address_during_leave' => ['required', 'string', 'max:255'],
            'emergency_contact' => ['required', 'string', 'max:20'],
        ];

        if ($this->leave_type === 'sick') {
            $rules['medical_certificate'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'leave_type.required' => 'Jenis cuti wajib dipilih.',
            'medical_certificate.required' => 'Surat dokter WAJIB dilampirkan untuk cuti sakit.',
            'medical_certificate.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG.',
            'medical_certificate.max' => 'Ukuran file maksimal 2MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $calculator = app(WorkingDayCalculator::class);
            $quotaService = app(LeaveQuotaService::class);
            
            if ($this->leave_type === 'annual' && !$calculator->isValidAnnualLeaveDate($this->start_date)) {
                $validator->errors()->add('start_date', 'Cuti tahunan harus diajukan minimal H+3.');
            }
            
            $totalDays = $calculator->calculate($this->start_date, $this->end_date);
            if ($totalDays <= 0) {
                $validator->errors()->add('end_date', 'Tidak ada hari kerja dalam rentang tanggal yang dipilih.');
                return;
            }
            
            if ($this->leave_type === 'annual' && !$quotaService->isAvailable(auth()->user(), $totalDays)) {
                $quota = $quotaService->getQuota(auth()->user());
                $validator->errors()->add('leave_type', "Kuota tidak mencukupi. Sisa: {$quota->remaining_quota} hari.");
            }

            if ($this->leave_type === 'annual' && !canRequestAnnualLeave(auth()->user())) {
                $remainingMonths = getRemainingMonthsToEligible(auth()->user());
                $validator->errors()->add(
                    'leave_type', 
                    "Anda belum memenuhi syarat untuk mengajukan cuti tahunan. Minimal masa kerja 1 tahun. Sisa waktu: {$remainingMonths} bulan lagi."
                );
            }
            
            // Check overlap & max pending
            $this->validateOverlap($validator);
            $this->validateMaxPending($validator);
        });
    }

    protected function validateOverlap($validator)
    {
        $hasOverlap = LeaveRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved_by_leader', 'approved'])
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function ($q) {
                          $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                      });
            })
            ->exists();

        if ($hasOverlap) {
            $validator->errors()->add('start_date', 'Anda sudah memiliki pengajuan cuti yang overlap.');
        }
    }

    protected function validateMaxPending($validator)
    {
        $hasPending = LeaveRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            $validator->errors()->add('leave_type', 'Anda masih memiliki 1 pengajuan pending.');
        }
    }
}
