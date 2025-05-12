<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminClaimController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $claims = Claim::with('user');

        if ($user->user_type_id === 3) {
            $claims = $claims->where('assigned_to', $user->id);
        }

        $claims = $claims->latest()->paginate(10);

        return view('backend.admin.claims.index', compact('claims'));
    }


    public function show(Claim $claim)
    {
        $claim->load(['documents', 'statusHistory', 'user']);
        return view('backend.admin.claims.show', compact('claim'));
    }

    public function updateStatus(Request $request, Claim $claim)
    {
        // Validate the incoming data
        $data = $request->validate([
            'status_id' => 'required|in:2,3,4',
            'notes' => 'nullable|string|max:1000',
        ]);

        if($data['status_id'] == 3) {
            $rejected = "Limekataliwa";
        } elseif($data['status_id'] == 2) {
            $rejected = "Limekubaliwa";
        } elseif($data['status_id'] == 4) {
            $rejected = "Limepitishwa Kwenye Uchunguzi";
        } else {
            $rejected = "Limekubaliwa";
        }


        // Update the claim status
        $claim->update(['status_id' => $request->status_id]);

        // Log the status change in the history
        $claim->statusHistory()->create([
            'status_id' => $request->status_id,
            'notes' => $request->notes,
        ]);

        $user = User::where('id', $claim->user_id)->first();
        if ($user->phone != null) {
            $phone = ltrim($user->phone, '0');
            $namba = "255" . $phone;

            $ujumbe = "Mpendwa " . $user->name . ", Ombi Lako lenye kumbukumbu namba " . $claim->reference . " ". $rejected . ". Asante!";

            $data = $this->sendSingleMessage($ujumbe, $namba);
            $dataArray = json_decode($data, true);

            if (!isset($dataArray['code']) || $dataArray['code'] != 100) {
                DB::rollBack();
                return response()->json([
                    'errors' => $dataArray['message'] ?? 'Failed to send message.'
                ]);
            }
        }

        return back()->with('success', 'Claim status updated successfully.');
    }



    public function assignForm(Claim $claim)
    {
        $staff = User::where('user_type_id', 3)->get();
        return view('backend.admin.claims.assign', compact('claim', 'staff'));
    }

    public function assignStaff(Request $request, Claim $claim)
    {
        $data =  $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = User::where('id', $data['assigned_to'])->first();

        if ($user->phone != null) {
            $phone = ltrim($user->phone, '0');
            $namba = "255" . $phone;

            $ujumbe = "Mpendwa " . $user->name . ", umepangiwa dai lenye kumbukumbu namba " . $claim->reference . ". Unaweza kuingia kwenye Mfumo kulitazama taarifa zake. Asante!";

            $data = $this->sendSingleMessage($ujumbe, $namba);
            $dataArray = json_decode($data, true);

            if (!isset($dataArray['code']) || $dataArray['code'] != 100) {
                DB::rollBack();
                return response()->json([
                    'errors' => $dataArray['message'] ?? 'Failed to send message.'
                ]);
            }
        }

        // Update the claim's assigned staff member
        $claim->update(['assigned_to' => $request->input('assigned_to')]);

        return redirect()->route('admin.claims.show', $claim)->with('success', 'Claim assigned to staff.');
    }

    public function pending()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 1) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('backend.admin.claims.index', compact('claims'));
    }

    public function approved()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 2) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('backend.admin.claims.index', compact('claims'));
    }

    public function rejected()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 3) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('backend.admin.claims.index', compact('claims'));
    }

    public function under_review()
    {
        $claims = Claim::with('documents')
            ->where('status_id', 4) // Assuming 1 is the ID for 'pending' status
            ->latest()
            ->paginate(10);

        return view('backend.admin.claims.index', compact('claims'));
    }
    public function sendSingleMessage($ujumbe,$namba){
        $api_key = env('BONGO_LIVE_KEY');
        $secret_key = env('BONGO_LIVE_SECRET');
        $sender_info = env('BONGO_SENDER_ID');

        $postData = array(
            'source_addr' => 'BRAINYIELD',
            'encoding'=>0,
            'schedule_time' => '',
            'message' => $ujumbe,
            'recipients' => [array('recipient_id' => '1','dest_addr'=>$namba)]
        );

        $Url ='https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if($response === FALSE){
            $data = $response;
            die(curl_error($ch));

            $data = "0000ATLA";
            return $data;
        }
        else{
            return $response;
        }
    }
}
