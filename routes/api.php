<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PicController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\api\CompanyController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\TopologyController;
use App\Http\Controllers\Api\GroupAreaController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\api\EnvironmentController;
use App\Http\Controllers\Api\VirtualMachineController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes 
| be assigned to the "api" middleware group. Make something great!for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
|
*/
// Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/applications', ApplicationController::class)->except(['index','show']);
    Route::post('/applications/import', [ApplicationController::class, 'import']);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/reviews', ReviewsController::class);
    Route::apiResource('/virtual_machines', VirtualMachineController::class);
    Route::apiResource('/technologies', TechnologyController::class);
    Route::apiResource('/topologies', TopologyController::class);
    Route::apiResource('/pics', PicController::class);
    Route::apiResource('/group_areas', GroupAreaController::class);
    Route::apiResource('/companies', CompanyController::class);
    Route::post('/validate-user', [UserController::class, 'validateUser']);
// });

Route::get('/applications', [ApplicationController::class, 'index']);
Route::get('/applications/{applications}', [ApplicationController::class, 'show']);
Route::get('/reviews', [ReviewsController::class, 'index']);

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');
Route::post('/password-reset', [LoginController::class, 'passwordReset']); 

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/logout', LogoutController::class)->name('logout');
Route::post('refresh', [LogoutController::class, 'refresh']);
