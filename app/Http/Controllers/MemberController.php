<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Status;
use Illuminate\Http\Request;

class MemberController extends Controller
{


    public function availableClaim()
    {

        $claims = Claim::with(['user', 'status'])
        ->latest()
        ->get();
        
        $statuses = Status::whereIn('name', ['approved', 'rejected'])->get();
        // print_r($stock);
        // exit;

        return view('backend.member.available_claim', compact('claims', 'statuses'));
    }

    
    public function filterByStatus($statusName)
    {
        $status = Status::where('name', $statusName)->firstOrFail();

        // $transactions = Claim::whereHas('status', function ($query) use ($statusName) {
        //     $query->where('name', $statusName);
        // })->with(['status'])->latest()->get();
        
        $claims = Claim::with([ 'status'])
        ->where('status_id', $status->id)
        ->latest()
        ->get();
        $statuses = Status::whereIn('name', ['pending', 'paid'])->get();

        return view('backend.member.index', compact('claims', 'statuses', 'statusName'));
    }
}
