<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClaimStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $statusIds = ClaimStatus::pluck('id', 'name');

        $total = Claim::where('user_id', $user->id)->count();
        $approved = Claim::where('user_id', $user->id)
            ->where('status_id', $statusIds['approved'])->count();
        $pending = Claim::where('user_id', $user->id)
            ->where('status_id', $statusIds['pending'])->count();
        $rejected = Claim::where('user_id', $user->id)
            ->where('status_id', $statusIds['rejected'])->count();

        $claims = Claim::with('status')->where('user_id', $user->id)->latest()->take(5)->get();

        return view('member.dashboard', compact('claims', 'total', 'approved', 'pending', 'rejected'));
    }
}
