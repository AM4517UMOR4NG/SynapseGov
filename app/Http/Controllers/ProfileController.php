<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Require current password if changing email address to prevent account takeover
        if ($request->email !== $user->email) {
            $request->validate([
                'current_password' => ['required', 'string'],
            ], [
                'current_password.required' => 'Password saat ini diperlukan untuk mengonfirmasi perubahan alamat email.',
            ]);

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withInput()->withErrors([
                    'current_password' => 'Password saat ini salah. Perubahan email dibatalkan.',
                ]);
            }
        }

        $data = $request->only([
            'name', 'email', 'phone', 'address', 'birth_date',
            'gender', 'position', 'bio',
        ]);

        // Prevent citizens from setting or modifying government position
        if ($user->isCitizen()) {
            unset($data['position']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        $settings = $user->getSettings();

        return view('profile.settings', compact('user', 'settings'));
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Handle AJAX/JSON settings update (e.g. from Theme Switcher or client fetch)
        if ($request->expectsJson() || $request->isJson()) {
            $validated = $request->validate([
                'theme' => 'nullable|in:light,dark',
                'language' => 'nullable|in:id,en',
                'dashboard_layout' => 'nullable|in:compact,comfortable,spacious',
                'items_per_page' => 'nullable|in:10,15,20,25,50',
            ]);

            $payload = array_filter($validated, fn ($val) => ! is_null($val));
            if (! empty($payload)) {
                $user->updateSettings($payload);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil diperbarui.',
                'settings' => $user->getSettings(),
            ]);
        }

        // Standard form submission validation
        $validated = $request->validate([
            'dashboard_layout' => 'required|in:compact,comfortable,spacious',
            'items_per_page' => 'required|in:10,15,25,50',
            'language' => 'required|in:id,en',
            'notifications' => 'nullable|array',
            'privacy' => 'nullable|array',
        ]);

        $settings = [
            'dashboard_layout' => $validated['dashboard_layout'],
            'items_per_page' => (int) $validated['items_per_page'],
            'language' => $validated['language'],
            'notifications' => [
                'email' => $request->boolean('notifications.email'),
                'browser' => $request->boolean('notifications.browser'),
                'sms' => $request->boolean('notifications.sms'),
                'reports' => $request->boolean('notifications.reports'),
                'complaints' => $request->boolean('notifications.complaints'),
                'status' => $request->boolean('notifications.status'),
            ],
            'privacy' => [
                'show_email' => $request->boolean('privacy.show_email'),
                'show_phone' => $request->boolean('privacy.show_phone'),
                'show_address' => $request->boolean('privacy.show_address'),
            ],
        ];

        try {
            $user->updateSettings($settings);

            return redirect()->route('profile.settings')->with('success', 'Pengaturan berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Settings update failed: '.$e->getMessage());

            return redirect()->route('profile.settings')->with('error', 'Gagal menyimpan pengaturan. Silakan coba lagi.');
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'password.different' => 'Password baru tidak boleh sama dengan password saat ini.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        // Prevent setting the same password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password saat ini.']);
        }

        // Invalidate sessions on other devices
        try {
            Auth::logoutOtherDevices($request->password);
        } catch (\Exception $e) {
            \Log::warning('Logout other devices notice: '.$e->getMessage());
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Password berhasil diperbarui dan sesi di perangkat lain telah diakhiri!');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->route('profile.edit')->with('success', 'Avatar berhasil dihapus!');
    }
}
