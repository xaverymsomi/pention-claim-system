<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Farmer;
use App\Models\Payout;
use App\Models\UserType;
use App\Models\CashewStock;
use App\Models\Cooperative;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\CashewDelivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'dashboard'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        $userTypes = UserType::where('id', '!=', 1)->get();

        // return view('auth.auth.register');
        return view('auth.auth.register', compact('userTypes'));
    }


public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:250',
        'phone_number' => ['required', 'string', 'digits:10', 'regex:/^0\d{9}$/'],
        'email' => 'required|email|max:250|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'user_type_id' => 'required|in:1,2,3,4' // must exist in user_types
    ];

    // Additional validation rules per user type
    if ($request->user_type_id == 2) { // Farmer
        $rules['location'] = 'required|string|max:255';
    }

    if ($request->user_type_id == 3) { // Buyer
        $rules['company'] = 'required|string|max:255';
        $rules['address'] = 'required|string|max:255';
    }

    if ($request->user_type_id == 4) { // Cooperative
        $rules['cooperative_name'] = 'required|string|max:255';
        $rules['region'] = 'required|string|max:255';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->all()]);
    }

    DB::beginTransaction();

    try {
        // Create user
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
        ]);

        // Insert to respective user type table
        if ($request->user_type_id == 2) { // Farmer
            Farmer::create([
                'user_id' => $user->id,
                'contact' => $request->phone_number,
                'location' => $request->location
            ]);
        }

        if ($request->user_type_id == 3) { // Buyer
            Buyer::create([
                'user_id' => $user->id,
                'company' => $request->company,
                'address' => $request->address
            ]);
        }

        if ($request->user_type_id == 4) { // Cooperative
            Cooperative::create([
                'user_id' => $user->id,
                'name' => $request->cooperative_name,
                'region' => $request->region
            ]);
        }

        // Send SMS if phone number is provided
        if ($request->phone_number != null) {
            $phone = ltrim($request->phone_number, '0');
            $namba = "255" . $phone;

            $ujumbe = "Mpendwa " . $request->name . ", umejiunga na mfumo wa CSRKMS.
Username: " . $request->email . ", Password: " . $request->password . ".
Ingia kuona taarifa zako. Asante!";

            $data = $this->sendSingleMessage($ujumbe, $namba);
            $dataArray = json_decode($data, true);

            if (!isset($dataArray['code']) || $dataArray['code'] != 100) {
                DB::rollBack();
                return response()->json([
                    'errors' => $dataArray['message'] ?? 'Failed to send message.'
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => 'You are successfully registered',
            'redirect' => route('login')
        ]);

        // Auto-login the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return response()->json([
                'success' => 'You are successfully registered',
                'redirect' => route('login')
            ]);
        }

        return response()->json(['error' => 'Account created but failed to log in.']);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Registration failed: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred during registration. Please try again.']);
    }
}

    // public function store(Request $request)
    // {
    //     $rules = [
    //         'name' => 'required|string|max:250',
    //         'phone_number' => ['required', 'string', 'digits:10', 'regex:/^0\d{9}$/'],
    //         'email' => 'required|email|max:250|unique:users,email',
    //         'password' => 'required|min:8|confirmed',
    //         'user_type_id' => 'required|in:1,2,3,4' // must exist in user_types
    //     ];

    //     // Additional validation rules per user type
    //     if ($request->user_type_id == 2) { // Farmer
    //         $rules['location'] = 'required|string|max:255';
    //     }

    //     if ($request->user_type_id == 3) { // Buyer
    //         $rules['company'] = 'required|string|max:255';
    //         $rules['address'] = 'required|string|max:255';
    //     }

    //     if ($request->user_type_id == 4) { // Cooperative
    //         $rules['cooperative_name'] = 'required|string|max:255';
    //         $rules['region'] = 'required|string|max:255';
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()->all()]);
    //     }

    //     // Create user
    //     $user = User::create([
    //         'name' => $request->name,
    //         'phone' => $request->phone_number,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'user_type_id' => $request->user_type_id,
    //     ]);

    //     // Insert to respective user type table
    //     if ($request->user_type_id == 2) { // Farmer
    //         Farmer::create([
    //             'user_id' => $user->id,
    //             'contact' => $request->phone_number,
    //             'location' => $request->location
    //         ]);
    //     }

    //     if ($request->user_type_id == 3) { // Buyer
    //         Buyer::create([
    //             'user_id' => $user->id,
    //             'company' => $request->company,
    //             'address' => $request->address
    //         ]);
    //     }

    //     if ($request->user_type_id == 4) { // Cooperative
    //         Cooperative::create([
    //             'user_id' => $user->id,
    //             'name' => $request->cooperative_name,
    //             'region' => $request->region
    //         ]);
    //     }




    //     if($request->phone_number != null){

    //         $phone = ltrim($request->phone_number, '0');

    //         $namba = "255".$phone;

    //         $ujumbe = "Mpendwa " . $request->name . ", umejiunga na mfumo wa CSRKMS.
    //         Username: " . $request->email . ", Password: " . $request->password . ".
    //         Ingia kuona taarifa zako. Asante!";

    //         $data = $this->sendSingleMessage($ujumbe,$namba);

    //         $dataArray = json_decode($data, true);

    //         if (isset($dataArray['code']) && $dataArray['code'] == 100) {

    //             return response()->json([
    //                 'success' => 'You are successfully registered',
    //                 'redirect' => route('login')
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'errors' => $dataArray['message']
    //             ]);
    //         }


    //         return response()->json(['error' => 'Account created but failed to log in.']);
    //     }
    //     return response()->json(['error' => 'Phone number is invalid.']);


    //     // Auto-login the user
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $request->session()->regenerate();
    //         return response()->json([
    //             'success' => 'You are successfully registered',
    //             'redirect' => route('dashboard')
    //         ]);
    //     }

    //     return response()->json(['error' => 'Account created but failed to log in.']);
    // }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.auth.welcome');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => 'You are Successful Logged In', 'redirect' => route('dashboard')]);
        }

        return response()->json(['errors' => 'Invalid Email/Password. Please try again']);
    }


    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        switch ($user->user_type_id) {
        case 1: // Admin
            $totalClaims = Claim::count();
            $approvedClaims = Claim::where('status_id', 2)->count();
            $rejectedClaims = Claim::where('status_id', 3)->count();
            $pendingClaims = Claim::where('status_id', 1)->count();
            $recentClaims = Claim::with(['user', 'status'])->latest()->take(5)->get();

            // Monthly Claims Data for Admin
            $monthlyClaims = Claim::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderByRaw('MONTH(created_at)')
                ->pluck('count', 'month');

            return view('backend.admin_dashboard', compact('user', 'totalClaims', 'approvedClaims', 'rejectedClaims', 'pendingClaims', 'recentClaims', 'monthlyClaims'));

        case 3: // Staff
            $assignedClaims = Claim::where('assigned_to', $user->id)->count();
            $resolvedClaims = Claim::where('assigned_to', $user->id)->whereIn('status_id', [2, 3])->count();
            $pendingStaffClaims = Claim::where('assigned_to', $user->id)->where('status_id', 1)->count();
            $staffRecentClaims = Claim::with(['user', 'status'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->take(5)
                ->get();

            // Monthly Claims Data for Staff
            $monthlyClaims = Claim::where('assigned_to', $user->id)
                ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderByRaw('MONTH(created_at)')
                ->pluck('count', 'month');

            return view('backend.staff_dashboard', compact('user', 'assignedClaims', 'resolvedClaims', 'pendingStaffClaims', 'staffRecentClaims', 'monthlyClaims'));

        case 2: // Member
            $total = Claim::where('user_id', $user->id)->count();
            $approved = Claim::where('user_id', $user->id)->where('status_id', 2)->count();
            $pending = Claim::where('user_id', $user->id)->where('status_id', 1)->count();

            // Fetch the most recent claims for the logged-in user
            $claims = Claim::with('status')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            // Pass the variables to the view
            return view('backend.member_dashboard', compact('user', 'total', 'approved', 'pending', 'claims'));

        default:
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
    }


    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgot_password(Request $request)
    {

        $rules = [
            'email' => 'required|email|max:250',
        ];

        $validator = Validator::make($request->all(), $rules);


        // Checking for any errors in validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $email = $request->input('email');

        // Generates an 8-digit random number for password
        $password = random_int(10000000, 99999999);

        // Find the user by email
        $user = User::where('email', $email)->first();

        if ($user) {

            $hash_password = Hash::make($password);
            $phone_number = $user->phone;
            $name = $user->name;
            // Update the user's password
            $update =  $user->update(['password' => $hash_password]);

            if ($update) {

                if ($phone_number != null) {

                    $phone = ltrim($phone_number, '0');
                    $namba = "255" . $phone;

                    $ujumbe = "Mpendwa " . $name .  " Password yako mpya ni " . $password . " Sasa unaweza kuingia kwenye mfumo. Tunakushukuru sana!";

                    $data = $this->sendSingleMessage($ujumbe, $namba);

                    if ($data) {
                        // return response()->json(['success' => 'Password reset and  Message sent Successful.']);
                        return response()->json(['success' => 'Password reset and  Message sent Successful.', 'redirect' => route('login')]);
                    } else {
                        return response()->json(['error' => 'Message Fail. Please Try Again.']);
                    }
                } else {
                    return response()->json(['errors' => 'Phone number can not be null.']);
                }
            }
            return response()->json(['errors' => 'Fail to reset Password. Please try again.']);
        }
        return response()->json(['errors' => 'User Not found. Please provide a valid Email.']);
    }


    // message
    public function sendSingleMessage($ujumbe, $namba)
    {
        $api_key = env('BONGO_LIVE_KEY');
        $secret_key = env('BONGO_LIVE_SECRET');
        $sender_info = env('BONGO_SENDER_ID');


        // $api_key = "31a33189623e6f7e";
        // $secret_key = "N2I3M2RkMDJiMzM4ZDI1ZmEzODE2OGNhOTdhZTU3OTcxZTU1ZDE1N2U2YjQ3ZjE2NjcyNzE3ZTY2N2IzNDNhMw==";

        // $sender_info = env('BONGO_SENDER_ID');

        $postData = array(
            'source_addr' => $sender_info,
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $ujumbe,
            'recipients' => [array('recipient_id' => '1', 'dest_addr' => $namba)]
        );

        $Url = 'https://apisms.beem.africa/v1/send';

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

        if ($response === FALSE) {
            $data = $response;
            die(curl_error($ch));

            $data = "0000ATLA";
            return $data;
        } else {
            // var_dump($response);
            return $response;
        }
    }


}
