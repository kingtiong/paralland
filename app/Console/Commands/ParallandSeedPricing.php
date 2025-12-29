<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\PricingModule;
use Illuminate\Console\Command;

class ParallandSeedPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paralland:seed-pricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed default pricing modules and base settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AppSetting::put('base_dev_usdt', AppSetting::getString('base_dev_usdt', '0.10'));
        AppSetting::put('base_monthly_usdt', AppSetting::getString('base_monthly_usdt', '20.00'));
        AppSetting::put('website_company_dev_usdt', AppSetting::getString('website_company_dev_usdt', '150.00'));
        AppSetting::put('website_personal_dev_usdt', AppSetting::getString('website_personal_dev_usdt', '80.00'));

        $defaults = [
            ['key' => 'admin_panel', 'label' => 'Admin Panel / Back Office', 'dev' => 200, 'monthly' => 0],
            ['key' => 'crm', 'label' => 'CRM (Leads, Customers, Tasks)', 'dev' => 150, 'monthly' => 0],
            ['key' => 'inventory', 'label' => 'Inventory / Stock', 'dev' => 180, 'monthly' => 0],
            ['key' => 'hr', 'label' => 'Human Resources (HR)', 'dev' => 200, 'monthly' => 0],
            ['key' => 'payment', 'label' => 'Payment Solution / Gateway Integration', 'dev' => 120, 'monthly' => 0],
            ['key' => 'marketplace', 'label' => 'Marketplace / E-commerce', 'dev' => 300, 'monthly' => 0],
            ['key' => 'mlm', 'label' => 'MLM Commission Plan', 'dev' => 500, 'monthly' => 0],
        ];

        $created = 0;
        foreach ($defaults as $d) {
            $m = PricingModule::query()->firstOrCreate(
                ['key' => $d['key']],
                [
                    'label' => $d['label'],
                    'dev_cost_usdt' => $d['dev'],
                    'monthly_cost_usdt' => $d['monthly'],
                    'is_active' => true,
                ]
            );
            if ($m->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->info("Seeded pricing. New modules created: {$created}.");
        return self::SUCCESS;
    }
}
