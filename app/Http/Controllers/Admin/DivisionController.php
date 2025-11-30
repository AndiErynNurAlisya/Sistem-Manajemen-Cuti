<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\DivisionStoreRequest;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

class DivisionController extends BaseController
{
    /**
     * Display a listing of divisions
     */
    public function index(Request $request)
    {
        $query = Division::with(['leader', 'members'])->withCount('members');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        if ($request->filled('leader')) {
            $query->whereHas('leader', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->leader}%")
                  ->orWhere('full_name', 'like', "%{$request->leader}%");
            });
        }
        
        if ($request->filled('member_count')) {
            $memberCount = $request->member_count;
            
            if ($memberCount === '0') {
                $query->has('members', '=', 0);
            } elseif ($memberCount === '50+') {
                $query->has('members', '>=', 50);
            } else {
                [$min, $max] = explode('-', $memberCount);
                $query->has('members', '>=', (int)$min)
                      ->has('members', '<=', (int)$max);
            }
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortColumns = ['name', 'created_at'];
        
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } 

        elseif ($sortBy === 'members_count') {
            $query->orderBy('members_count', $sortOrder);
        }
        else {
            $query->orderBy('created_at', 'desc');
        }
        
        $divisions = $this->paginate($query);
                
        return view('admin.divisions.index', compact('divisions'));
    }
    
    /**
     * Show the form for creating a new division
     */
    public function create()
    {
        // Get users dengan role leader yang belum jadi ketua divisi
        $availableLeaders = User::where('role', 'leader')
            ->whereDoesntHave('leadingDivision')
            ->get();
        
        return view('admin.divisions.create', compact('availableLeaders'));
    }
    
    /**
     * Store a newly created division
     */
    public function store(DivisionStoreRequest $request)
    {
        return $this->transaction(function() use ($request) {
            $division = Division::create($request->validated());
            
            return $division;
        },
        'Divisi berhasil ditambahkan!',
        'Gagal menambahkan divisi');
    }
    
    /**
     * Display the specified division
     */
    public function show(Division $division)
    {
        $division->load(['leader', 'members.leaveQuota']);
        
        // Get available users (belum punya divisi)
        $availableUsers = User::whereIn('role', ['employee', 'leader'])
            ->whereNull('division_id')
            ->orderBy('full_name')
            ->get();
        
        return view('admin.divisions.show', compact('division', 'availableUsers'));
    }
    
    /**
     * Show the form for editing division
     */
    public function edit(Division $division)
    {
        // Get available leaders (belum jadi ketua divisi lain)
        $availableLeaders = User::where('role', 'leader')
            ->where(function($q) use ($division) {
                $q->whereDoesntHave('leadingDivision')
                  ->orWhere('id', $division->leader_id);
            })
            ->get();
        
        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }
    
    /**
     * Update the specified division
     */
    public function update(DivisionStoreRequest $request, Division $division)
    {
        return $this->transaction(function() use ($request, $division) {
            $division->update($request->validated());
            
            return $division;
        },
        'Divisi berhasil diupdate!',
        'Gagal mengupdate divisi');
    }
    
    /**
     * Remove the specified division
     * 
     */
    public function destroy(Request $request, Division $division)
    {
        // 🆕 Validasi konfirmasi nama divisi
        $request->validate([
            'division_name_confirmation' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($division) {
                    if ($value !== $division->name) {
                        $fail('Nama divisi tidak sesuai. Ketik "' . $division->name . '" untuk konfirmasi.');
                    }
                },
            ],
        ]);
        
        return $this->transaction(function() use ($division) {
            $division->members()->update(['division_id' => null]);
            
            $division->delete();
            
            return $division;
        },
        'Divisi berhasil dihapus. Semua anggota sekarang tidak memiliki divisi.',
        'Gagal menghapus divisi');
    }
    
    /**
     * Add member to division
     */
    public function addMember(Request $request, Division $division)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // PASTIKAN user belum punya divisi
        if ($user->division_id) {
            return back()->withErrors(['user_id' => 'User sudah tergabung di divisi lain.']);
        }

        // Set division_id ke user
        $user->update([
            'division_id' => $division->id,
        ]);

        if ($user->role->value === 'leader' && !$division->leader_id) {
            $division->update([
                'leader_id' => $user->id,
            ]);
        }

        return back()->with('success', "{$user->full_name} berhasil ditambahkan ke divisi {$division->name}.");
    }
    
    /**
     * Remove member from division
     */
    public function removeMember(Division $division, User $user)
    {
        // Cek apakah user adalah leader
        if ($division->leader_id === $user->id) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus Ketua Divisi. Ubah ketua terlebih dahulu.']);
        }

        $user->update([
            'division_id' => null,
        ]);

        return back()->with('success', "{$user->full_name} berhasil dihapus dari divisi.");
    }
}