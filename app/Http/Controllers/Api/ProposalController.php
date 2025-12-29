<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Proposal::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'development_price_rbe' => ['nullable', 'integer', 'min:2'],
        ]);

        $proposal = Proposal::create([
            'user_id' => $user->id,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'requirements' => $data['requirements'] ?? null,
            'development_price_rbe' => $data['development_price_rbe'] ?? 2,
            'status' => 'submitted',
        ]);

        return response()->json($proposal, 201);
    }

    public function show(Request $request, Proposal $proposal)
    {
        $user = $request->user();
        if ($proposal->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return $proposal;
    }
}
