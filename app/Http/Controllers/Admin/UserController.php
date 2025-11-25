<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Models\Division;
use App\Services\LeaveQuotaService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $quotaService;
    
    public function __construct(LeaveQuotaService $quotaService)
    {
        $this->quotaService = $quotaService;
    }
    
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('division');
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter by division
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $users = $this->paginate($query);
        $divisions = Division::all();
        
        return view('admin.users.index', compact('users', 'divisions'));
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $divisions = Division::all();
        $adminExists = User::where('role', 'admin')->exists();
        $hrdExists = User::where('role', 'hrd')->exists();

        return view('admin.users.create', compact('divisions', 'adminExists', 'hrdExists'));
    }

    
    /**
     * Store a newly created user
     */
    public function store(UserStoreRequest $request)
    {
        return $this->transaction(function() use ($request) {
            $data = $request->validated();
            
            // Hash password
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $this->uploadFile(
                    $request->file('profile_photo'),
                    'profile_photos'
                );
            }
            
            // Create user
            $user = User::create($data);
            
            // 🔥 AUTO-SET LEADER: Jika role = leader dan punya divisi
            if ($user->role->value === 'leader' && $user->division_id) {
                $division = Division::find($user->division_id);
                
                // Set sebagai leader jika divisi belum punya leader
                if ($division && !$division->leader_id) {
                    $division->update(['leader_id' => $user->id]);
                }
            }
            
            // Initialize kuota jika employee atau leader
            if (in_array($user->role->value, ['employee', 'leader'])) {
                $this->quotaService->initialize($user);
            }
            
            return $user;
        }, 
        'User berhasil ditambahkan!',
        'Gagal menambahkan user');
    }
    
    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['division', 'leaveRequests', 'leaveQuota']);
        
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Show the form for editing user
     */
    public function edit(User $user)
    {
        $divisions = Division::all();

        // Cek apakah sudah ada admin lain
        $adminExists = User::where('role', 'admin')->exists();

        // Cek apakah sudah ada HRD lain
        $hrdExists = User::where('role', 'hrd')->exists();

        return view('admin.users.edit', compact(
            'user',
            'divisions',
            'adminExists',
            'hrdExists'
        ));
    }

    /**
     * Update the specified user
     */
    public function update(UserStoreRequest $request, User $user)
    {
        return $this->transaction(function() use ($request, $user) {
            $data = $request->validated();
            
            // Simpan data lama untuk tracking perubahan
            $oldDivisionId = $user->division_id;
            $oldRole = $user->role->value;
            
            // Hash password jika diisi
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($user->profile_photo) {
                    $this->deleteFile($user->profile_photo);
                }
                
                $data['profile_photo'] = $this->uploadFile(
                    $request->file('profile_photo'),
                    'profile_photos'
                );
            }
            
            // Update user
            $user->update($data);
            
            // 🔥 HANDLE LEADER SYNC LOGIC
            
            // 1. Jika berubah jadi leader DAN punya divisi
            if ($user->role->value === 'leader' && $user->division_id) {
                $division = Division::find($user->division_id);
                
                // Set sebagai leader jika divisi belum punya leader
                if ($division && !$division->leader_id) {
                    $division->update(['leader_id' => $user->id]);
                }
            }
            
            // 2. Jika dulu leader tapi sekarang role berubah
            if ($oldRole === 'leader' && $user->role->value !== 'leader') {
                // Hapus dari leader_id divisi
                Division::where('leader_id', $user->id)
                    ->update(['leader_id' => null]);
            }
            
            // 3. Jika pindah divisi dan dia adalah leader di divisi lama
            if ($oldDivisionId && $oldDivisionId != $user->division_id) {
                Division::where('id', $oldDivisionId)
                    ->where('leader_id', $user->id)
                    ->update(['leader_id' => null]);
                    
                // Jika role = leader, set sebagai leader di divisi baru (jika belum ada)
                if ($user->role->value === 'leader' && $user->division_id) {
                    $newDivision = Division::find($user->division_id);
                    if ($newDivision && !$newDivision->leader_id) {
                        $newDivision->update(['leader_id' => $user->id]);
                    }
                }
            }
            
            return $user;
        },
        'User berhasil diupdate!',
        'Gagal mengupdate user');
    }
    
    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === $this->user()->id) {
            return $this->error('Anda tidak bisa menghapus akun sendiri!');
        }
        
        // Tidak bisa hapus jika punya pending leave
        if ($user->leaveRequests()->where('status', 'pending')->exists()) {
            return $this->error('User tidak bisa dihapus karena masih ada pengajuan cuti pending.');
        }
        
        return $this->transaction(function() use ($user) {
            // 🔥 Hapus dari leader_id jika dia adalah leader
            if ($user->role->value === 'leader') {
                Division::where('leader_id', $user->id)
                    ->update(['leader_id' => null]);
            }
            
            // Delete profile photo
            if ($user->profile_photo) {
                $this->deleteFile($user->profile_photo);
            }
            
            $user->delete();
            
            return $user;
        },
        'User berhasil dihapus!',
        'Gagal menghapus user');
    }
}