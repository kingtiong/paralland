<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Proposal::query()
            ->with(['user', 'reviewedBy', 'project'])
            ->latest();

        if (is_string($status) && $status !== '') {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['submitted', 'revision_requested']);
        }

        return $query->get();
    }

    public function review(Request $request, Proposal $proposal)
    {
        $user = $request->user();

        $data = $request->validate([
            'action' => ['required', 'string', 'in:accept,revision,reject'],
            'note' => ['nullable', 'string'],
            'development_price_rbe' => ['nullable', 'integer', 'min:2'],
        ]);

        $action = $data['action'];

        $updates = [
            'reviewed_by_user_id' => $user->id,
            'review_note' => $data['note'] ?? null,
            'reviewed_at' => now(),
        ];

        if (isset($data['development_price_rbe'])) {
            $updates['development_price_rbe'] = $data['development_price_rbe'];
        }

        if ($action === 'accept') {
            $updates['status'] = 'accepted';
        } elseif ($action === 'revision') {
            $updates['status'] = 'revision_requested';
        } else {
            $updates['status'] = 'rejected';
        }

        $proposal->fill($updates)->save();

        if ($action === 'accept') {
            $project = $proposal->project;
            if (!$project) {
                $project = Project::create([
                    'proposal_id' => $proposal->id,
                    'client_user_id' => $proposal->user_id,
                    'status' => 'proposal_accepted',
                ]);

                ProjectMessage::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'sender_type' => 'system',
                    'message' => 'Proposal accepted. Development will start now and will be delivered within 24 hours.',
                ]);
            }
        }

        return $proposal->load(['user', 'reviewedBy', 'project']);
    }
}
