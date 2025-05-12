<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::with('documents')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('member.claims.index', compact('claims'));
    }
    public function pending()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 1) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('member.claims.index', compact('claims'));
    }

    public function approved()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 2) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('member.claims.index', compact('claims'));
    }

    public function rejected()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 3) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('member.claims.index', compact('claims'));
    }

    public function under_review()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 4) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('member.claims.index', compact('claims'));
    }

    public function show(Claim $claim)
    {
        // Ensure member can only view their own claim
        if ($claim->user_id !== auth()->id()) {
            abort(403);
        }

        $claim->load('documents');

        $claim->load(['documents', 'statusHistory']);

//        dd($claim->documents());

        return view('member.claims.show', compact('claim'));
    }
}
