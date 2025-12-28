<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $members = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('email', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'q' => $q,
        ]);
    }

    public function show(User $user): View
    {
        $orders = $user->proposals()->latest()->paginate(20);

        return view('admin.members.show', [
            'member' => $user,
            'orders' => $orders,
        ]);
    }
}

