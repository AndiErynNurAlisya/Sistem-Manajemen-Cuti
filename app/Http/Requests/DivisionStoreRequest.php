<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Division;

class DivisionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $divisionId = $this->route('division') ? $this->route('division')->id : null;
        
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('divisions')->ignore($divisionId)],
            'description' => ['nullable', 'string'],
            'leader_id' => ['nullable', 'exists:users,id'],
            'established_date' => ['required', 'date'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->leader_id) {
                $divisionId = $this->route('division') ? $this->route('division')->id : null;
                
                $isLeaderTaken = Division::where('leader_id', $this->leader_id)
                    ->when($divisionId, fn($q) => $q->where('id', '!=', $divisionId))
                    ->exists();
                
                if ($isLeaderTaken) {
                    $validator->errors()->add('leader_id', 'User ini sudah menjadi ketua divisi lain.');
                }
            }
        });
    }
}

