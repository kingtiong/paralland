<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceInvoice;
use Illuminate\View\View;
use Illuminate\Http\Request;

class MaintenanceInvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $filter = (string) $request->query('filter', 'all');

        $q = MaintenanceInvoice::query()
            ->with(['project.proposal', 'project.client'])
            ->orderByDesc('due_date');

        $now = now()->startOfDay();
        if ($filter === 'pending_60') {
            $q->where('status', 'due')->whereDate('due_date', '<=', $now->copy()->subDays(60));
        } elseif ($filter === 'pending_30') {
            $q->where('status', 'due')->whereDate('due_date', '<=', $now->copy()->subDays(30));
        } elseif ($filter === 'overdue') {
            $q->where('status', 'overdue');
        } elseif ($filter === 'due') {
            $q->where('status', 'due');
        } elseif ($filter === 'paid') {
            $q->where('status', 'paid');
        }

        $invoices = $q->paginate(25)->withQueryString();

        $counts = [
            'due' => MaintenanceInvoice::query()->where('status', 'due')->count(),
            'overdue' => MaintenanceInvoice::query()->where('status', 'overdue')->count(),
            'pending_30' => MaintenanceInvoice::query()->where('status', 'due')->whereDate('due_date', '<=', $now->copy()->subDays(30))->count(),
            'pending_60' => MaintenanceInvoice::query()->where('status', 'due')->whereDate('due_date', '<=', $now->copy()->subDays(60))->count(),
        ];

        return view('admin.maintenance.index', [
            'invoices' => $invoices,
            'filter' => $filter,
            'counts' => $counts,
        ]);
    }
}
