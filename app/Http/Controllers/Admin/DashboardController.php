<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationMessage;
use App\Models\MaintenanceInvoice;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $messagesLast24h = ConversationMessage::query()
            ->where('created_at', '>=', $now->copy()->subDay())
            ->count();

        $recentMessages = ConversationMessage::query()
            ->with(['conversation', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $pending30 = MaintenanceInvoice::query()
            ->where('status', 'due')
            ->whereDate('due_date', '<=', $now->copy()->startOfDay()->subDays(30))
            ->count();
        $pending60 = MaintenanceInvoice::query()
            ->where('status', 'due')
            ->whereDate('due_date', '<=', $now->copy()->startOfDay()->subDays(60))
            ->count();
        $overdue = MaintenanceInvoice::query()->where('status', 'overdue')->count();

        $stats = [
            'members' => User::query()->count(),
            'orders_total' => Proposal::query()->count(),
            'orders_pending_payment' => Proposal::query()->where('payment_status', 'pending')->count(),
            'orders_unpaid' => Proposal::query()->where('payment_status', 'unpaid')->count(),
            'orders_new' => Proposal::query()->where('work_status', 'new')->count(),
            'orders_in_progress' => Proposal::query()->where('work_status', 'in_progress')->count(),
            'orders_waiting_client' => Proposal::query()->where('work_status', 'waiting_client')->count(),
            'messages_last_24h' => $messagesLast24h,
            'maintenance_pending_30' => $pending30,
            'maintenance_pending_60' => $pending60,
            'maintenance_overdue' => $overdue,
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentMessages' => $recentMessages,
        ]);
    }
}

