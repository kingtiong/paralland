<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Conversation;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Proposal::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Legacy form kept for compatibility; prefer wizard.
        $modules = $this->availableModules();
        return view('orders.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'modules' => ['required', 'array', 'min:1'],
            'modules.*' => ['string', 'max:50'],
            'requirements' => ['nullable', 'string'],
        ]);

        $available = collect($this->availableModules())->pluck('key')->all();
        $selected = array_values(array_unique(array_intersect($data['modules'], $available)));
        if (count($selected) === 0) {
            return back()->withErrors(['modules' => 'Please select at least one valid module.'])->withInput();
        }

        $requirements = null;
        if (!empty($data['requirements'])) {
            $requirements = ['notes' => $data['requirements']];
        }

        $order = Proposal::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'requested_modules' => $selected,
            'requirements' => $requirements,
            'development_price_rbe' => 2,
            'status' => 'submitted',
        ]);

        return redirect()->route('orders.show', $order);
    }

    public function show(Request $request, Proposal $order)
    {
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        $conversation = Conversation::query()
            ->where('type', 'order')
            ->where('proposal_id', $order->id)
            ->with(['messages.user'])
            ->first();

        return view('orders.show', [
            'order' => $order,
            'conversation' => $conversation,
        ]);
    }

    /**
     * @return array<int, array{key:string,label:string}>
     */
    private function availableModules(): array
    {
        return [
            ['key' => 'website', 'label' => 'Website (Company Profile / Landing)'],
            ['key' => 'admin_panel', 'label' => 'Admin Panel / Back Office'],
            ['key' => 'crm', 'label' => 'CRM (Leads, Customers, Tasks)'],
            ['key' => 'inventory', 'label' => 'Inventory / Stock'],
            ['key' => 'hr', 'label' => 'Human Resources (HR)'],
            ['key' => 'payment', 'label' => 'Payment Solution / Gateway Integration'],
            ['key' => 'marketplace', 'label' => 'Marketplace / E-commerce'],
            ['key' => 'mlm', 'label' => 'MLM Commission Plan'],
        ];
    }
}
