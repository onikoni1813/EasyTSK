<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    public function index()
    {
        if (! Auth::user()->is_moderator && ! Auth::user()->is_admin) {
            abort(403);
        }

        $submissions = Submission::with(['task', 'user'])->where('status', 'pending')->latest()->paginate(20);

        return view('moderator.submissions.index', compact('submissions'));
    }
}
