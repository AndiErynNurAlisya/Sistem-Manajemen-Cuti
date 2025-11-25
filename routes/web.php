<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use App\Http\Controllers\Leader;
use App\Http\Controllers\HRD;
use App\Http\Controllers\ProfileController; // Tambahkan ini
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role->dashboardRoute());
    }
    return redirect()->route('login');
});

// Auth routes (dari Breeze) 
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (UMUM UNTUK SEMUA ROLE)
|--------------------------------------------------------------------------
*/
// Rute ini akan memiliki nama 'profile.edit', 'profile.update', dan 'profile.destroy'
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', Admin\UserController::class);
        
        // Division Management
        Route::resource('divisions', Admin\DivisionController::class);
        Route::post('divisions/{division}/members', [Admin\DivisionController::class, 'addMember'])->name('divisions.members.add');
        Route::delete('divisions/{division}/members/{user}', [Admin\DivisionController::class, 'removeMember'])->name('divisions.members.remove');
    });

/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('employee')
    ->name('employee.')
    ->middleware(['auth', 'role:employee'])
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [Employee\DashboardController::class, 'index'])->name('dashboard');
        
        // Leave Requests
        Route::get('leave-requests', [Employee\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/create', [Employee\LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('leave-requests', [Employee\LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('leave-requests/{leaveRequest}', [Employee\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('leave-requests/{leaveRequest}/cancel', [Employee\LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
        
        // Download letters
        Route::get('leave-requests/{leaveRequest}/download-request', [Employee\LeaveRequestController::class, 'downloadRequestLetter'])->name('leave-requests.download-request');
        Route::get('leave-requests/{leaveRequest}/download-approval', [Employee\LeaveRequestController::class, 'downloadApprovalLetter'])->name('leave-requests.download-approval');
    });

/*
|--------------------------------------------------------------------------
| LEADER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('leader')
    ->name('leader.')
    ->middleware(['auth', 'role:leader'])
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [Leader\DashboardController::class, 'index'])->name('dashboard');
        
        // Approval (untuk anggota divisi)
        Route::get('approvals', [Leader\LeaveApprovalController::class, 'index'])->name('approvals.index');
        Route::get('approvals/{leaveRequest}', [Leader\LeaveApprovalController::class, 'show'])->name('approvals.show');
        Route::post('approvals/{leaveRequest}/approve', [Leader\LeaveApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('approvals/{leaveRequest}/reject', [Leader\LeaveApprovalController::class, 'reject'])->name('approvals.reject');
        
        // Leave Requests (cuti pribadi leader)
        Route::get('leave-requests', [Leader\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/create', [Leader\LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('leave-requests', [Leader\LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('leave-requests/{leaveRequest}', [Leader\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('leave-requests/{leaveRequest}/cancel', [Leader\LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    });

/*
|--------------------------------------------------------------------------
| HRD ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('hrd')
    ->name('hrd.')
    ->middleware(['auth', 'role:hrd'])
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [HRD\DashboardController::class, 'index'])->name('dashboard');
        
        // Final Approval
        Route::get('final-approvals', [HRD\FinalApprovalController::class, 'index'])->name('final-approvals.index');
        Route::get('final-approvals/{leaveRequest}', [HRD\FinalApprovalController::class, 'show'])->name('final-approvals.show');
        Route::post('final-approvals/{leaveRequest}/approve', [HRD\FinalApprovalController::class, 'approve'])->name('final-approvals.approve');
        Route::post('final-approvals/{leaveRequest}/reject', [HRD\FinalApprovalController::class, 'reject'])->name('final-approvals.reject');
        Route::post('final-approvals/batch', [HRD\FinalApprovalController::class, 'batchApprove'])->name('final-approvals.batch');
    });