<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingUpdateRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\ActivityLog;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Mengambil semua setting dan mengubahnya menjadi format yang mudah diakses di view
        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the application settings.
     */
    public function update(SettingUpdateRequest $request)
    {
        $validated = $request->validated();

        // Menangani penghapusan logo terlebih dahulu
        if ($request->boolean('delete_logo')) {
            $oldLogo = setting('app_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
                Setting::where('key', 'app_logo')->delete();
            }
        }

        // Menangani upload logo baru
        if ($request->hasFile('app_logo')) {
            $oldLogo = setting('app_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $validated['app_logo'] = $request->file('app_logo')->store('logos', 'public');
        }

        // Loop dan simpan semua pengaturan lainnya
        foreach ($validated as $key => $value) {
            // Lewati field ini karena sudah ditangani atau tidak perlu disimpan
            if ($key === 'delete_logo' || is_null($value)) continue;

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Hapus cache setting agar perubahan langsung terlihat
        Cache::forget('app_settings');
        
        ActivityLog::addLog('UPDATE_SETTINGS', "Memperbarui pengaturan aplikasi.");

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }   
}
