<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return success response dengan redirect
     */
    protected function success(string $message, $route = null, $data = null)
    {
        if ($route) {
            return redirect()->route($route)->with('success', $message);
        }
        
        return back()->with('success', $message)->with('data', $data);
    }

    /**
     * Return error response
     */
    protected function error(string $message, $route = null)
    {
        if ($route) {
            return redirect()->route($route)->with('error', $message);
        }
        
        return back()->with('error', $message)->withInput();
    }

    /**
     * Execute database transaction dengan error handling
     * Usage: return $this->transaction(function() { ... }, 'Success!', 'Failed!');
     */
    protected function transaction(callable $callback, string $successMessage = 'Operasi berhasil', string $errorMessage = 'Operasi gagal')
    {
        DB::beginTransaction();
        
        try {
            $result = $callback();
            DB::commit();
            
            return $this->success($successMessage, data: $result);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($errorMessage . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->error($errorMessage . ': ' . $e->getMessage());
        }
    }

    /**
     * Get authenticated user
     */
    protected function user()
    {
        return auth()->user();
    }

    /**
     * Check if user has role
     */
    protected function hasRole(string $role): bool
    {
        return $this->user()->role->value === $role;
    }

    /**
     * Validate ownership (untuk resource yang punya user_id)
     */
    protected function validateOwnership($resource, $userId = null)
    {
        $userId = $userId ?? $this->user()->id;
        
        if ($resource->user_id !== $userId && !$this->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke resource ini.');
        }
    }

    /**
     * Handle file upload
     * Usage: $path = $this->uploadFile($request->file('photo'), 'profile_photos');
     */
    protected function uploadFile($file, string $folder = 'uploads', string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, $disk);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file from storage
     */
    protected function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        try {
            return \Storage::disk($disk)->delete($path);
        } catch (\Exception $e) {
            Log::error('File delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Paginate with custom per page
     */
    protected function paginate($query, $perPage = 10)
    {
        $perPage = request('per_page', $perPage);
        return $query->paginate($perPage)->withQueryString();
    }
}