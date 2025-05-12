<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CashewDelivery;
use App\Models\Payout;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin dashboard
        if ($user->user_type_id == 1) {
            // Add your existing admin dashboard logic here
            return view('dashboard');
        }

        // Farmer dashboard
        if ($user->user_type_id == 2 && $user->farmer) {
            $farmerId = $user->farmer->id;

            $total_deliveries = CashewDelivery::where('farmer_id', $farmerId)->count();

            $approved_deliveries = CashewDelivery::where('farmer_id', $farmerId)
                ->whereHas('status', fn ($q) => $q->where('name', 'approved'))
                ->count();

            $pending_deliveries = CashewDelivery::where('farmer_id', $farmerId)
                ->whereHas('status', fn ($q) => $q->where('name', 'pending'))
                ->count();

            $total_payouts = Payout::whereHas('delivery', function ($q) use ($farmerId) {
                $q->where('farmer_id', $farmerId);
            })->sum('amount');

            return view('dashboard', compact(
                'total_deliveries',
                'approved_deliveries',
                'pending_deliveries',
                'total_payouts'
            ));
        }

        // Add logic for buyer and cooperative if needed
        return view('dashboard');
    }
}
