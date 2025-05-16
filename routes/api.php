<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\CutController;
use App\Http\Controllers\api\ColorController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ShapeController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\api\PolishController;
use App\Http\Controllers\api\ClarityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\DiamondMaster\DiamondMasterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// get all diamonds data
Route::get('/get-all-diamonds', [DiamondMasterController::class, 'data']);
Route::post('/contact', [ContactController::class, 'submit']);

// ->middleware('throttle:10,1')

Route::get('/diamond-shapes', [ShapeController::class, 'getFrontShapes']);
Route::get('diamonds/by-shape/{shape_id}', [ShapeController::class, 'filterDiamondsByShape']);
//color
Route::get('/diamond-colors', [ShapeController::class, 'getFrontColors']);
Route::get('diamonds/by-color/{color_id}', [ColorController::class, 'filterDiamondsByColor']);
//Cut
Route::get('diamonds/by-cut/{cut_id}', [CutController::class, 'filterDiamondsByCut']);
//clarity
Route::get('diamonds/by-clarity/{clarity_id}', [ClarityController::class, 'filterDiamondsByClarity']);

Route::get('diamonds/by-polish/{polish_id}', [PolishController::class, 'filterDiamondsByPolish']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/password/email', [AuthController::class, 'sendResetLink']);
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'forgetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/reset-password-auth', [AuthController::class, 'resetPassword']);

});



