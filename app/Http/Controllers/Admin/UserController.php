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
     * 
     */
    public function index(Request $request)
    {
        $query = User::with('division');
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        if ($request->filled('tenure')) {
            $now = now();
            
            switch ($request->tenure) {
                case 'less_6':
                    $query->where('join_date', '>=', $now->copy()->subMonths(6));
                    break;
                case '6_12':
                    $query->whereBetween('join_date', [
                        $now->copy()->subMonths(12),
                        $now->copy()->subMonths(6)
                    ]);
                    break;
                case 'more_12':
                    $query->where('join_date', '<', $now->copy()->subMonths(12));
                    break;
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortColumns = ['full_name', 'join_date', 'created_at'];
        
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } 
        elseif ($sortBy === 'division') {
            $query->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                  ->select('users.*')
                  ->orderBy('divisions.name', $sortOrder);
        }
        else {
            $query->orderBy('created_at', 'desc');
        }
        
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

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $this->uploadFile(
                    $request->file('profile_photo'),
                    'profile_photos'
                );
            }

            $user = User::create($data);

            if ($user->role->value === 'leader' && $user->division_id) {
                $division = Division::find($user->division_id);
                
                if ($division && !$division->leader_id) {
                    $division->update(['leader_id' => $user->id]);
                }
            }

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

        $adminExists = User::where('role', 'admin')->exists();

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

            $oldDivisionId = $user->division_id;
            $oldRole = $user->role->value;
            
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            
            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo) {
                    $this->deleteFile($user->profile_photo);
                }
                
                $data['profile_photo'] = $this->uploadFile(
                    $request->file('profile_photo'),
                    'profile_photos'
                );
            }
            
            $user->update($data);
            
            
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
        if ($user->id === $this->user()->id) {
            return $this->error('Anda tidak bisa menghapus akun sendiri!');
        }
        
        if ($user->leaveRequests()->where('status', 'pending')->exists()) {
            return $this->error('User tidak bisa dihapus karena masih ada pengajuan cuti pending.');
        }
        
        return $this->transaction(function() use ($user) {
            if ($user->role->value === 'leader') {
                Division::where('leader_id', $user->id)
                    ->update(['leader_id' => null]);
            }
            
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