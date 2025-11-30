<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use App\Http\Controllers\Leader;
use App\Http\Controllers\HRD;
use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role->dashboardRoute());
    }
    return redirect()->route('login');
});

require __DIR__.'/auth.php';


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('users', Admin\UserController::class);

        Route::resource('divisions', Admin\DivisionController::class);
        Route::post('divisions/{division}/members', [Admin\DivisionController::class, 'addMember'])->name('divisions.members.add');
        Route::delete('divisions/{division}/members/{user}', [Admin\DivisionController::class, 'removeMember'])->name('divisions.members.remove');

        Route::get('reports', [Admin\ReportsController::class, 'index'])->name('reports.index');
    });


Route::prefix('employee')
    ->name('employee.')
    ->middleware(['auth', 'role:employee'])
    ->group(function () {
        
        Route::get('/dashboard', [Employee\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('leave-requests', [Employee\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/create', [Employee\LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('leave-requests', [Employee\LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('leave-requests/{leaveRequest}', [Employee\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('leave-requests/{leaveRequest}/cancel', [Employee\LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
        
        Route::get('leave-requests/{leaveRequest}/download-request', [Employee\LeaveRequestController::class, 'downloadRequestLetter'])->name('leave-requests.download-request');
        Route::get('leave-requests/{leaveRequest}/download-approval', [Employee\LeaveRequestController::class, 'downloadApprovalLetter'])->name('leave-requests.download-approval');
    });


Route::prefix('leader')
    ->name('leader.')
    ->middleware(['auth', 'role:leader'])
    ->group(function () {

        Route::get('/dashboard', [Leader\DashboardController::class, 'index'])->name('dashboard');

        Route::get('approvals', [Leader\LeaveApprovalController::class, 'index'])->name('approvals.index');
        Route::get('approvals/{leaveRequest}', [Leader\LeaveApprovalController::class, 'show'])->name('approvals.show');
        Route::post('approvals/{leaveRequest}/approve', [Leader\LeaveApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('approvals/{leaveRequest}/reject', [Leader\LeaveApprovalController::class, 'reject'])->name('approvals.reject');
        
        Route::get('leave-requests', [Leader\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/create', [Leader\LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('leave-requests', [Leader\LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('leave-requests/{leaveRequest}', [Leader\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('leave-requests/{leaveRequest}/cancel', [Leader\LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
        
        Route::get('leave-requests/{leaveRequest}/download-request', [Leader\LeaveRequestController::class, 'downloadRequest'])->name('leave-requests.download-request');
        Route::get('leave-requests/{leaveRequest}/download-approval', [Leader\LeaveRequestController::class, 'downloadApproval'])->name('leave-requests.download-approval');

    });

Route::prefix('hrd')
    ->name('hrd.')
    ->middleware(['auth', 'role:hrd'])
    ->group(function () {
        
        Route::get('/dashboard', [HRD\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('final-approvals', [HRD\FinalApprovalController::class, 'index'])->name('final-approvals.index');
        Route::get('final-approvals/{leaveRequest}', [HRD\FinalApprovalController::class, 'show'])->name('final-approvals.show');
        Route::post('final-approvals/{leaveRequest}/approve', [HRD\FinalApprovalController::class, 'approve'])->name('final-approvals.approve');
        Route::post('final-approvals/{leaveRequest}/reject', [HRD\FinalApprovalController::class, 'reject'])->name('final-approvals.reject');
        Route::post('final-approvals/batch', [HRD\FinalApprovalController::class, 'batchApprove'])->name('final-approvals.batch');
        Route::post('final-approvals/batch-reject', [HRD\FinalApprovalController::class, 'batchReject'])->name('final-approvals.batch-reject');

        Route::get('history-cuti', [HRD\HistoryCutiController::class, 'index'])->name('history-cuti.index');
    });