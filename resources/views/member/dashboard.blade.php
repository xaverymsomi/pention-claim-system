@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }} 👋</h1>
        <p class="text-sm text-gray-500">Here's a summary of your pension claims.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Total Claims</p>
            <p class="text-2xl font-bold text-blue-600">{{ $total }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-2xl font-bold text-green-600">{{ $approved }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $pending }}</p>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Claims</h2>
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="p-2">Claim Ref</th>
                <th class="p-2">Status</th>
                <th class="p-2">Submitted</th>
            </tr>
            </thead>
            <tbody>
            @forelse($claims as $claim)
                <tr class="border-b">
                    <td class="p-2">{{ $claim->reference }}</td>
                    <td class="p-2 capitalize
                            @if($claim->status->name === 'approved') text-green-600
                            @elseif($claim->status->name === 'pending') text-yellow-500
                            @else text-red-600 @endif">
                        {{ $claim->status->name }}
                    </td>
                    <td class="p-2">{{ $claim->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-2 text-center text-gray-500">No claims yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
