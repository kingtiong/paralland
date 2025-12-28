<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOrderStep1Request;
use App\Http\Requests\SaveOrderStep2Request;
use App\Http\Requests\SaveOrderStep3Request;
use App\Http\Requests\SubmitOrderPaymentRequest;
use App\Models\OrderFile;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderWizardController extends Controller
{
    public function start(Request $request)
    {
        $payTo = (string) config('services.usdt_bep20.treasury_address', env('USDT_BEP20_TREASURY_ADDRESS', ''));

        $order = Proposal::create([
            'user_id' => $request->user()->id,
            'title' => 'Draft order',
            'description' => null,
            'status' => 'draft',
            'wizard_step' => 1,
            'payment_chain' => 'BEP20',
            'payment_status' => 'unpaid',
            'payment_to_address' => $payTo ?: null,
        ]);

        return redirect()->route('orders.wizard.step1', $order);
    }

    public function step1(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);
        return view('orders.wizard.step1', ['order' => $order]);
    }

    public function saveStep1(SaveOrderStep1Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validated();

        $products = $this->splitLines($data['products_services'] ?? '');

        $needWebsite = (bool) ($data['need_website'] ?? false);
        $websiteType = (string) ($data['website_type'] ?? 'company');
        if (!in_array($websiteType, ['company', 'personal'], true)) {
            $websiteType = 'company';
        }

        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        $order->wizard_step1 = [
            'need_website' => $needWebsite,
            'website_type' => $websiteType,
            'website_url' => $data['website_url'] ?? null,
            'purpose' => $data['purpose'],
            'title' => $title ?: null,
            'description' => $description ?: null,
            'address' => $data['address'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'industry' => $data['industry'] ?? null,
            'products_services' => $products,
        ];

        // Keep a reasonable title for listings, even if user skips details.
        if ($title !== '') {
            $order->title = $title;
        } elseif (!$order->title || $order->title === 'Draft order') {
            $order->title = 'Draft order';
        }

        if ($description !== '') {
            $order->description = $description;
        }

        $order->wizard_step = max((int) $order->wizard_step, 2);
        $order->save();

        return redirect()->route('orders.wizard.step2', $order);
    }

    public function step2(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $existing = $order->wizard_step2 ?? [];
        $suggestions = $this->suggestContent($order);

        $files = $order->orderFiles()->latest()->get();

        return view('orders.wizard.step2', [
            'order' => $order,
            'existing' => $existing,
            'suggestions' => $suggestions,
            'files' => $files,
        ]);
    }

    public function saveStep2(SaveOrderStep2Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validated();

        $order->wizard_step2 = [
            'intro' => $data['intro'] ?? null,
            'business_nature' => $data['business_nature'] ?? null,
            'advantages' => $data['advantages'] ?? null,
            'services' => $data['services'] ?? null,
            'team_members' => $data['team_members'] ?? null,
        ];
        $order->wizard_step = max((int) $order->wizard_step, 3);
        $order->save();

        return redirect()->route('orders.wizard.step3', $order);
    }

    public function uploadStep2(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validate([
            'kind' => ['nullable', 'string', 'max:50'],
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        foreach ($request->file('images', []) as $file) {
            $uuid = (string) Str::uuid();
            $path = "private/orders/{$order->id}/images/{$uuid}";
            $stored = $file->storeAs(dirname($path), basename($path), 'local');

            OrderFile::create([
                'proposal_id' => $order->id,
                'uploaded_by_user_id' => $request->user()->id,
                'kind' => $data['kind'] ?? 'image',
                'original_name' => $file->getClientOriginalName(),
                'path' => $stored,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'meta' => [
                    'ext' => $file->getClientOriginalExtension(),
                ],
            ]);
        }

        return back()->with('status', 'Images uploaded.');
    }

    public function step3(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $modules = $this->availableModules();

        return view('orders.wizard.step3', [
            'order' => $order,
            'modules' => $modules,
        ]);
    }

    public function saveStep3(SaveOrderStep3Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $available = collect($this->availableModules())->pluck('key')->all();
        $selected = array_values(array_unique(array_intersect($request->validated()['modules'] ?? [], $available)));

        $previous = $order->requested_modules ?? [];
        $added = array_values(array_diff($selected, $previous));

        $order->requested_modules = $selected;
        $order->wizard_step = max((int) $order->wizard_step, 4);

        // If the member adds new functions after the order was already paid/verified,
        // require another payment confirmation (difference is handled in Step 5).
        if (count($added) > 0 && ($order->paid_total_usdt !== null || $order->payment_verified_at !== null)) {
            $order->payment_status = 'unpaid';
            $order->status = 'pending_payment';
            $order->payment_tx_hash = null;
            $order->payment_from_address = null;
        }

        $order->save();

        return redirect()->route('orders.wizard.step4', $order);
    }

    public function step4(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $estimate = $this->estimateCost($order);

        return view('orders.wizard.step4', [
            'order' => $order,
            'estimate' => $estimate,
        ]);
    }

    public function confirmStep4(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $estimate = $this->estimateCost($order);

        $order->wizard_step4_estimate = $estimate;
        $order->estimated_total_usdt = $estimate['total_usdt'];
        $order->wizard_step = max((int) $order->wizard_step, 5);
        $order->status = 'pending_payment';
        $order->submitted_at = now();
        $order->save();

        return redirect()->route('orders.wizard.step5', $order);
    }

    public function step5(Request $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $payTo = $order->payment_to_address ?: (string) config('services.usdt_bep20.treasury_address', env('USDT_BEP20_TREASURY_ADDRESS', ''));
        $estimated = (float) ($order->estimated_total_usdt ?? 0);
        $paid = (float) ($order->paid_total_usdt ?? 0);
        $amountDue = max(0.0, $estimated - $paid);

        return view('orders.wizard.step5', [
            'order' => $order,
            'payTo' => $payTo,
            'amountDue' => $amountDue,
            'paidTotal' => $paid,
        ]);
    }

    public function submitStep5(SubmitOrderPaymentRequest $request, Proposal $order)
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validated();

        $order->payment_from_address = $data['payment_from_address'] ?? null;
        $order->payment_tx_hash = $data['payment_tx_hash'] ?? null;
        $order->payment_status = 'pending';
        $order->save();

        return redirect()->route('orders.show', $order)->with('status', 'Payment submitted. We will verify your transaction.');
    }

    private function authorizeOrder(Request $request, Proposal $order): void
    {
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }
    }

    /**
     * @return array<int, array{key:string,label:string,price_usdt:int}>
     */
    private function availableModules(): array
    {
        return [
            ['key' => 'admin_panel', 'label' => 'Admin Panel / Back Office', 'price_usdt' => 200],
            ['key' => 'crm', 'label' => 'CRM (Leads, Customers, Tasks)', 'price_usdt' => 150],
            ['key' => 'inventory', 'label' => 'Inventory / Stock', 'price_usdt' => 180],
            ['key' => 'hr', 'label' => 'Human Resources (HR)', 'price_usdt' => 200],
            ['key' => 'payment', 'label' => 'Payment Solution / Gateway Integration', 'price_usdt' => 120],
            ['key' => 'marketplace', 'label' => 'Marketplace / E-commerce', 'price_usdt' => 300],
            ['key' => 'mlm', 'label' => 'MLM Commission Plan', 'price_usdt' => 500],
        ];
    }

    private function estimateCost(Proposal $order): array
    {
        $step1 = $order->wizard_step1 ?? [];
        $needWebsite = (bool) Arr::get($step1, 'need_website', true);
        $websiteType = (string) Arr::get($step1, 'website_type', 'company');

        $items = [];

        if ($needWebsite) {
            $items[] = $websiteType === 'personal'
                ? ['key' => 'base_personal', 'label' => 'Base personal website', 'price_usdt' => 80]
                : ['key' => 'base_company', 'label' => 'Base company website', 'price_usdt' => 150];
        }

        $selected = $order->requested_modules ?? [];
        $catalog = collect($this->availableModules())->keyBy('key');

        foreach ($selected as $key) {
            if ($catalog->has($key)) {
                $items[] = $catalog->get($key);
            }
        }

        $total = collect($items)->sum('price_usdt');

        return [
            'currency' => 'USDT',
            'chain' => 'BEP20',
            'items' => $items,
            'total_usdt' => (float) $total,
            'note' => 'Estimate only. Final quote may change after review of requirements.',
        ];
    }

    private function suggestContent(Proposal $order): array
    {
        $step1 = $order->wizard_step1 ?? [];
        $industry = (string) Arr::get($step1, 'industry', 'your industry');
        $purpose = (string) Arr::get($step1, 'purpose', 'your goals');
        $products = Arr::get($step1, 'products_services', []);
        $productsLine = is_array($products) && count($products) ? implode(', ', array_slice($products, 0, 5)) : 'our products and services';

        return [
            'intro' => "We help customers with {$productsLine}. Built for {$purpose}, we focus on quality, speed, and reliable support.",
            'business_nature' => "We operate in {$industry} and deliver practical solutions tailored to customer needs.",
            'advantages' => "- Clear process and fast delivery\n- Transparent pricing\n- Ongoing support and maintenance",
            'services' => "- {$productsLine}\n- Consultation and support\n- Custom development",
            'team_members' => "- Founder / Director\n- Project Manager\n- Developer\n- Support",
        ];
    }

    /**
     * @return array<int, string>
     */
    private function splitLines(string $text): array
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($text)) ?: [];
        $lines = array_values(array_filter(array_map('trim', $lines), fn ($v) => $v !== ''));
        return array_slice($lines, 0, 50);
    }
}
