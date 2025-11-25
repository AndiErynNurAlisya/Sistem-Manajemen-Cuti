<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isLeader() || auth()->user()->isHRD());
    }

    public function rules(): array
    {
        $rules = ['notes' => ['nullable', 'string', 'max:500']];

        if ($this->routeIs('*.reject')) {
            $rules['notes'] = ['required', 'string', 'min:10', 'max:500'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Alasan penolakan wajib diisi.',
            'notes.min' => 'Alasan minimal 10 karakter.',
        ];
    }
}