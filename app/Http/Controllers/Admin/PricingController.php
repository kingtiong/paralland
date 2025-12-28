<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\PricingModule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(): View
    {
        $modules = PricingModule::query()->orderBy('label')->get();

        $settings = [
            'base_dev_usdt' => AppSetting::getDecimal('base_dev_usdt', 0.10),
            'base_monthly_usdt' => AppSetting::getDecimal('base_monthly_usdt', 20.00),
            'website_company_dev_usdt' => AppSetting::getDecimal('website_company_dev_usdt', 150.00),
            'website_personal_dev_usdt' => AppSetting::getDecimal('website_personal_dev_usdt', 80.00),
        ];

        return view('admin.pricing.index', [
            'modules' => $modules,
            'settings' => $settings,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'base_dev_usdt' => ['required', 'numeric', 'min:0'],
            'base_monthly_usdt' => ['required', 'numeric', 'min:0'],
            'website_company_dev_usdt' => ['required', 'numeric', 'min:0'],
            'website_personal_dev_usdt' => ['required', 'numeric', 'min:0'],

            'modules' => ['nullable', 'array'],
            'modules.*.label' => ['required', 'string', 'max:255'],
            'modules.*.description' => ['nullable', 'string', 'max:4000'],
            'modules.*.dev_cost_usdt' => ['required', 'numeric', 'min:0'],
            'modules.*.monthly_cost_usdt' => ['required', 'numeric', 'min:0'],
            'modules.*.is_active' => ['nullable', 'boolean'],
            'modules.*.delete' => ['nullable', 'boolean'],

            'new_module_key' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/'],
            'new_module_label' => ['nullable', 'string', 'max:255'],
            'new_module_description' => ['nullable', 'string', 'max:4000'],
            'new_module_dev_cost_usdt' => ['nullable', 'numeric', 'min:0'],
            'new_module_monthly_cost_usdt' => ['nullable', 'numeric', 'min:0'],
        ]);

        AppSetting::put('base_dev_usdt', (string) $data['base_dev_usdt']);
        AppSetting::put('base_monthly_usdt', (string) $data['base_monthly_usdt']);
        AppSetting::put('website_company_dev_usdt', (string) $data['website_company_dev_usdt']);
        AppSetting::put('website_personal_dev_usdt', (string) $data['website_personal_dev_usdt']);

        foreach (($data['modules'] ?? []) as $id => $row) {
            $m = PricingModule::query()->find($id);
            if (!$m) {
                continue;
            }

            if (!empty($row['delete'])) {
                $m->delete();
                continue;
            }

            $m->label = $row['label'];
            $m->description = $row['description'] ?? null;
            $m->dev_cost_usdt = $row['dev_cost_usdt'];
            $m->monthly_cost_usdt = $row['monthly_cost_usdt'];
            $m->is_active = (bool) ($row['is_active'] ?? false);
            $m->save();
        }

        if (!empty($data['new_module_key']) && !empty($data['new_module_label'])) {
            PricingModule::query()->firstOrCreate(
                ['key' => $data['new_module_key']],
                [
                    'label' => $data['new_module_label'],
                    'description' => $data['new_module_description'] ?? null,
                    'dev_cost_usdt' => (float) ($data['new_module_dev_cost_usdt'] ?? 0),
                    'monthly_cost_usdt' => (float) ($data['new_module_monthly_cost_usdt'] ?? 0),
                    'is_active' => true,
                ]
            );
        }

        return back()->with('status', 'Pricing saved.');
    }
}

