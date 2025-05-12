<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClaimWizardController extends Controller
{

    public function index()
    {
        return view('member.claims.step1');
    }
    public function step1()
    {
        return view('member.claims.step1');
    }

    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        // Store in session
        session(['claim.step1' => $data]);

        return redirect()->route('member.claims.step2');
    }

    public function step2()
    {
        return view('member.claims.step2');
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'national_id' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nssf_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'retirement_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Store files temporarily or path in session
        $files = [];

        foreach (['national_id', 'nssf_card', 'retirement_letter'] as $field) {
            if ($request->hasFile($field)) {
                $files[$field] = $request->file($field)->store("tmp_claims/".auth()->id(), 'public');
            }
        }

        session(['claim.step2' => $files]);

        return redirect()->route('member.claims.step3');
    }

    public function step3()
    {
        $step1 = session('claim.step1');
        $step2 = session('claim.step2');

        if (!$step1 || !$step2) {
            return redirect()->route('member.claims.step1')->with('error', 'Incomplete claim data.');
        }

        return view('member.claims.step3', compact('step1', 'step2'));
    }

    public function submit()
    {
        $user = Auth::user();
        $step1 = session('claim.step1');
        $step2 = session('claim.step2');

        if (!$step1 || !$step2) {
            return redirect()->route('member.claims.step1')->with('error', 'Missing session data.');
        }

        // Create Claim
        $claim = Claim::create([
            'user_id' => $user->id,
            'reference' => 'CLM-' . strtoupper(Str::random(6)),
            'status' => 'pending',
            'comments' => $step1['notes'] ?? null,
        ]);

        // Move uploaded files and create ClaimDocuments
        foreach ($step2 as $type => $path) {
            $newPath = str_replace('tmp_claims', 'claims', $path);
            Storage::disk('public')->move($path, $newPath);

            $claim->documents()->create([
                'document_type' => $type,
                'file_path' => $newPath,
                'status' => 'uploaded',
            ]);
        }

        // Clear session
        session()->forget('claim');

        return redirect()->route('member.claims')->with('success', 'Claim submitted successfully!');
    }
}
