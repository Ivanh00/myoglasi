<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class VerificationDocumentController extends Controller
{
    public function show($path)
    {
        $user = auth()->user();

        if (!$user || !$user->is_admin) {
            abort(403);
        }

        $fullPath = 'verification_documents/' . $path;

        if (!Storage::disk('local')->exists($fullPath)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($fullPath));
    }
}
