<?php

namespace App\Http\Controllers;

use App\Models\OrderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderFileController extends Controller
{
    public function download(Request $request, OrderFile $file)
    {
        $order = $file->order;
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($file->path)) {
            abort(404);
        }

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    public function destroy(Request $request, OrderFile $file)
    {
        $order = $file->order;
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        Storage::disk('local')->delete($file->path);
        $file->delete();

        return back()->with('status', 'File deleted.');
    }
}
