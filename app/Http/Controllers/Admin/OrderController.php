<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\Proposal;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Proposal::query()
            ->with(['user', 'project'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Proposal $order)
    {
        $order->load(['user', 'reviewedBy', 'project']);
        return view('admin.orders.show', compact('order'));
    }

    public function review(Request $request, Proposal $order)
    {
        $data = $request->validate([
            'action' => ['required', 'string', 'in:accept,revision,reject'],
            'note' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        $order->reviewed_by_user_id = $user->id;
        $order->review_note = $data['note'] ?? null;
        $order->reviewed_at = now();

        if ($data['action'] === 'accept') {
            $order->status = 'accepted';
        } elseif ($data['action'] === 'revision') {
            $order->status = 'revision_requested';
        } else {
            $order->status = 'rejected';
        }

        $order->save();

        if ($data['action'] === 'accept') {
            $project = $order->project;
            if (!$project) {
                $project = Project::create([
                    'proposal_id' => $order->id,
                    'client_user_id' => $order->user_id,
                    'status' => 'proposal_accepted',
                ]);

                ProjectMessage::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'sender_type' => 'system',
                    'message' => 'Order accepted. Development will start now and will be delivered within 24 hours.',
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order);
    }
}
