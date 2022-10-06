<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /**
     * Create the Class Instance
     */
    public function __construct()
    {
        $this->middleware('role:master|admin');
    }
    
    public function index($type = 'general')
    {
        if( $type == 'index' ){ $type = 'general'; }
        if( ! view()->exists("admin.settings.{$type}") ){ abort(404); }

        return view('admin.settings.index', compact('type'));
    }

    public function update(Request $request, $type = 'general') {
        $request->validate([
            'setting_app_name' => 'required|sometimes|string|max:100'
        ], [
            'setting_app_name.required' => "Application name is required!"
        ]);
        $updates = [];
        foreach ($request->all() as $key => $value) {
            if( str()->startsWith($key, 'setting_') ){
                $updates[] = Setting::setValue(str_replace('setting_', '', $key), $value );
            }
        }
        Setting::cachedSettings(true);
        return back()->withSuccess("Update Successfully!");
    }
}
