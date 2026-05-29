<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'auto_assign_tickets', 'email_notifications', 'maintenance_mode',
            'push_notifications', 'sound_alerts', 'desktop_notifications',
            'two_factor_auth', 'session_timeout', 'ip_whitelist'
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->has($key) ? '1' : '0']
            );
        }

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
}
