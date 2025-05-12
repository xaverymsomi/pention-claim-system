<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordRequest;
use Illuminate\Console\View\Components\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the role from the request query parameter
        $role = $request->get('role');

        // Filter users by user_type_id based on the role
        $users = User::when($role, function($query) use ($role) {
            // Map role names to user_type_id for better flexibility
            $userType = match ($role) {
                'admin' => 1,
                'staff' => 2,
                'member' => 3,
                default => null,
            };

            if ($userType !== null) {
                $query->where('user_type_id', $userType);
            }
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.admin.users.index', compact('users', 'role'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'user_type_id' => 'required|in:1,2,3',
            'password' => 'required|confirmed|min:6',
        ]);

        $password = $data['password'];

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        if ($data['phone'] != null) {
            $phone = ltrim($data['phone'], '0');
            $namba = "255" . $phone;

            $ujumbe = "Mpendwa " . $data['name'] . ", umejiunga na mfumo wa NSSF.
Username: " . $data['email'] . ", Password: " . $password . ".
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


        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    // message
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('backend.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type_id' => 'required|in:1,2,3',
            'password' => 'nullable|confirmed|min:6',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }


    public function user_profile(){
        $personal_details = User::findOrFail(auth()->user()->id);

        if($personal_details == ""){
            Alert::info("No user information");
            return redirect('dashboard');
        }

        else{
            return view('backend.user.profile', compact(['personal_details']));
        }
    }

      //updating profile details
    public function profile_update(Request $request){

        $existing = User::findOrFail(auth()->user()->id);
        // $old_photo = $existing->picha;
        //checking if we have other changes from for other fields
        $rules = [
            'name' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        //checking for any errors in validation
        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza taarifa zote za muhimu, barua pepe na namba visiwe vimetumika.."]]);
        }


        //validating
        $data_update = [
            'name' => $request->name,
        ];

        //updating
        $success = User::whereId(auth()->user()->id)->update($data_update);

        if($success){
                    Alert::success("<h2 class='text-primary'>Taarifa</h2>","Umefanikiwa kubadili nywila yako kikamilifu, tafadhali ingia tena kwenye mfumo kwa kutumia neno la siri jipya ulilojaza hivi punde..")->autoclose(6000)->timerProgressBar(6000);

            return response()->json(['success' => "<h2 class='text-primary'>Taarifa</h2>","Password changed Successful"]);

            return response()->json(['success' => 'Profile updated successfully']);
            return redirect('users_profile');
        }
        else{

            return response()->json(['errors' => 'Profile updated failed. Please try again']);

            return back();
        }
    }

    //updating password
    public function password_update(PasswordRequest $request,User $user){

        // return "hello";
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        // return response()->json(['success' => "<h2 class='text-primary'>Taarifa</h2>","Password changed Successful"]);

        // Alert::success("<h2 class='text-primary'>Taarifa</h2>","Umefanikiwa kubadili nywila yako kikamilifu, tafadhali ingia tena kwenye mfumo kwa kutumia neno la siri jipya ulilojaza hivi punde..")->autoclose(6000)->timerProgressBar(6000);
        Auth::logout();
        return redirect('/');
    }


    public function allContent()
    {
        $title = 'All Content';
        $user_id = Auth::user()->id;

        if($user_id){

            $all_content = Content::where('user_id', $user_id)->get();
            $all_content_no = content::all()->count();

        }

        return View('backend.content.all_content',compact(['all_content', 'title' ]));
    }

    public function newContent()
    {
        $title = 'New Content';

        $user_id = Auth::user()->id;

        if($user_id){
            $new_content_no = content::all()->count();

            $new_content = Content::where('user_id', $user_id)
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        }



        return View('backend.content.new_content',compact(['new_content', 'title' ]));
    }

    public function verifiedContent()
    {
        $title = 'Verified Content';

        $user_id = Auth::user()->id;

        if($user_id){

            $verified_content_no = content::all()->count();
            $verified_content = Content::where('user_id', $user_id)
                                ->where('approval_status', 'verified')->orderBy('created_at', 'desc')->get();
        }


        return View('backend.content.verified_content',compact(['verified_content', 'title' ]));
    }

    public function rejectedContent()
    {
        $title = 'Rejected Content';

        $user_id = Auth::user()->id;

        if($user_id){
            $rejected_content_no = content::all()->count();
            $rejected_content = Content::where('user_id', $user_id)
                                ->where('approval_status', 'rejected')->orderBy('created_at', 'desc')->get();
        }

        return View('backend.content.rejected_content',compact(['rejected_content', 'title' ]));
    }



}
