<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'members' => User::query()->count(),
            'orders_total' => Proposal::query()->count(),
            'orders_pending_payment' => Proposal::query()->where('payment_status', 'pending')->count(),
            'orders_unpaid' => Proposal::query()->where('payment_status', 'unpaid')->count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}

