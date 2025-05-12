<?php

use App\Http\Controllers\Admin\AdminClaimController;
use App\Http\Controllers\Member\ClaimWizardController;
use App\Http\Controllers\Member\MemberClaimController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClaimController;

// use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Admin\PriceController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// user start route
Route::get('users_profile',[UserController::class,'user_profile']);
Route::get('/users',[UserController::class,'index']);
Route::resource('/users',UserController::class);


Route::get('users_profile',[UserController::class,'user_profile']);
Route::get('viongozi/destroy/{id}','UserController@destroy');
Route::post('user_profile/update',[UserController::class,'profile_update'])->name("users.profile_update");
Route::post('user_profile/password',[UserController::class,'password_update'])->name("users.password_update");

Route::get('/user_contents',[UserController::class,'index'])->name('contents');
Route::get('/user_all_contents',[UserController::class,'allContent'])->name('all_contents');
Route::get('/user_new_contents',[UserController::class,'newContent']);
Route::get('/user_verified_contents',[UserController::class,'verifiedContent']);
Route::get('/user_rejected_contents',[UserController::class,'rejectedContent']);

// user end

// Farmer route start


// end Routes for Cooperative

// Buyer Panel Routes
Route::middleware(['auth', 'staff'])->prefix('buyer')->group(function () {
    Route::get('available-claim', [ClaimController::class, 'availableClaim'])->name('staff.available.claims');
    Route::get('/claim/status/{status}', [ClaimController::class, 'filterByStatus'])->name('claims.status');

});

// end Routes for Buyer
//Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
//    Route::resource('users', UserController::class);
//});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/dashboard', fn() => view('backend.admin.dashboard'))->name('dashboard');
});

Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/claims/create/step-1', [ClaimWizardController::class, 'step1'])->name('claims.step1');
    Route::post('/claims/create/step-1', [ClaimWizardController::class, 'storeStep1'])->name('claims.storeStep1');
    Route::get('/claims/create/step-2', [ClaimWizardController::class, 'step2'])->name('claims.step2');
    Route::post('/claims/create/step-2', [ClaimWizardController::class, 'storeStep2'])->name('claims.storeStep2');
    Route::get('/claims/create/step-3', [ClaimWizardController::class, 'step3'])->name('claims.step3');
    Route::post('/claims/create/submit', [ClaimWizardController::class, 'submit'])->name('claims.submit');
    Route::get('/claims', [MemberClaimController::class, 'index'])->name('claims');
    Route::get('/pending_claims/', [MemberClaimController::class, 'pending'])->name('pending.claims');
    Route::get('/approved_claims/', [MemberClaimController::class, 'approved'])->name('approved.claims');
    Route::get('/rejected_claims/', [MemberClaimController::class, 'rejected'])->name('rejected.claims');
    Route::get('/under_review_claims/', [MemberClaimController::class, 'under_review'])->name('under_review.claims');
    Route::get('/claims/{claim}', [MemberClaimController::class, 'show'])->name('claims.show');
});

// End Routes for Admin



Route::controller(AuthController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('new_user.store');
    Route::get('/', 'login')->name('login');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    // Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/authenticate', 'authenticate')->name('authenticate');

    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');

    Route::post('/forgot_password', 'forgot_password')->name('password.email');

});


// route for register Admin user

Route::controller(UserController::class)->group(function() {

    Route::post('/user_store', 'store')->name('user.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/claims', [AdminClaimController::class, 'index'])->name('claims');
    Route::get('/claims/{claim}', [AdminClaimController::class, 'show'])->name('claims.show');
    Route::get('/pending_claims/', [AdminClaimController::class, 'pending'])->name('pending.claims');
    Route::get('/approved_claims/', [AdminClaimController::class, 'approved'])->name('approved.claims');
    Route::get('/rejected_claims/', [AdminClaimController::class, 'rejected'])->name('rejected.claims');
    Route::get('/under_review_claims/', [AdminClaimController::class, 'under_review'])->name('under_review.claims');
    Route::post('/claims/{claim}/status', [AdminClaimController::class, 'updateStatus'])->name('claims.updateStatus');
    Route::get('/claims/{claim}/assign', [AdminClaimController::class, 'assignForm'])->name('claims.assign');
    Route::post('/claims/{claim}/assign', [AdminClaimController::class, 'assignStaff'])->name('claims.assignStaff');

});



