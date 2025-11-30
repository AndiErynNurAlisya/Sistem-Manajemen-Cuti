<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\AvatarUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load('division.leader');

        $quota = null;
        if (in_array($user->role->value, ['employee', 'leader'])) {
            $quota = getLeaveQuota($user);
        }

        return view('profile.edit', [
            'user' => $user,
            'quota' => $quota,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(AvatarUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('profile_photo')) {
            $path = uploadProfilePhoto($request->file('profile_photo'), $user->profile_photo);

            $user->profile_photo = $path;
            $user->save();

            return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil diperbarui!');
        }

        return Redirect::route('profile.edit')->with('error', 'Gagal mengupload foto profil.');
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo) {
            deleteProfilePhoto($user->profile_photo);
            $user->profile_photo = null;
            $user->save();

            return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil dihapus!');
        }

        return Redirect::route('profile.edit')->with('info', 'Tidak ada foto profil untuk dihapus.');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return Redirect::route('profile.edit')
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Password berhasil diperbarui!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user->profile_photo) {
            deleteProfilePhoto($user->profile_photo);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
