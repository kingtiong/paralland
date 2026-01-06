<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\PricingModule;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingEstimateTest extends TestCase
{
    use RefreshDatabase;

    public function test_estimate_is_base_plus_selected_modules_with_yearly_discount(): void
    {
        // Base: dev 0.50, server 20/mo
        AppSetting::put('base_dev_usdt', '0.50');
        AppSetting::put('base_monthly_usdt', '20.00');
        AppSetting::put('website_company_dev_usdt', '0.00');
        AppSetting::put('website_personal_dev_usdt', '0.00');

        // Module: membership adds dev 50, server 1/mo
        PricingModule::query()->create([
            'key' => 'membership',
            'label' => 'Membership',
            'description' => null,
            'dev_cost_usdt' => 50.00,
            'monthly_cost_usdt' => 1.00,
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $order = Proposal::query()->create([
            'user_id' => $user->id,
            'title' => 'Draft order',
            'description' => null,
            'status' => 'draft',
            'wizard_step' => 4,
            'wizard_step1' => [
                'need_website' => false,
                'website_type' => 'company',
                'purpose' => 'Test',
            ],
            'requested_modules' => ['membership'],
        ]);

        $res = $this->actingAs($user)->get(route('orders.wizard.step4', $order, absolute: false));

        $res->assertOk();

        // Dev total: 0.50 + 50.00 = 50.50
        $res->assertSee('50.50 USDT (dev)');

        // Monthly total: 20.00 + 1.00 = 21.00
        $res->assertSee('21.00 USDT / month');

        // Yearly total: 21.00 * 12 * 0.8 = 201.60
        $res->assertSee('201.60 USDT / year');
    }
}

