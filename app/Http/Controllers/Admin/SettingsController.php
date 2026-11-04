<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Brand;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'presets' => config('brand.presets'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'business_type' => 'required|in:' . implode(',', array_keys(config('brand.presets'))),
            'tagline' => 'nullable|string|max:200',
            'phone' => ['nullable', 'regex:/^05\d{8}$/'],
            'whatsapp' => ['nullable', 'regex:/^9665\d{8}$/'],
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:250',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:150',
            'bank_iban' => ['nullable', 'regex:/^SA\d{22}$/'],
            'bank_account_number' => 'nullable|string|max:30',
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'service_label' => 'nullable|string|max:50',
            'provider_label' => 'nullable|string|max:50',
            'logo_file' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo_file')) {
            $data['logo'] = '/storage/' . $request->file('logo_file')->store('brand', 'public');
        }
        unset($data['logo_file']);

        Brand::save(array_map(fn ($v) => $v ?? '', $data));

        return redirect()->route('admin.settings.edit')->with('success', 'تم حفظ الإعدادات');
    }
}
