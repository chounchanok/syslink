<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\JobsubmitController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::post('/reset-password',[JobsubmitController::class,'reset_password']);

Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    //get
    Route::get('/get_job/all',[JobsubmitController::class,'get_job_all']);
    Route::post('/get_job',[JobsubmitController::class,'get_job']);
    Route::get('/profile',[AuthController::class,'profile']);
    Route::get('/year',[JobsubmitController::class,'year']);
    Route::post('/calendar',[JobsubmitController::class,'calendar']);
    //post
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/back_step/submit',[JobsubmitController::class,'back_step_job']);
    Route::post('/project/submit',[JobsubmitController::class,'submit_project']);
    Route::post('/get_job/filter',[JobsubmitController::class,'get_job_filter']);
    Route::post('/get_job/filter2',[JobsubmitController::class,'get_job_filter_test']);
    Route::post('/job_submit',[JobsubmitController::class,'submit_file']);
    Route::post('/job_submit_signature',[JobsubmitController::class,'submit_signature']);
    Route::post('/job_submit_in_progress',[JobsubmitController::class,'job_submit_in_progress']);
    Route::post('/check/list',[JobsubmitController::class,'check_list']);
    Route::post('/image/get',[JobsubmitController::class,'image_get']);
    Route::post('/image/survay',[JobsubmitController::class,'image_app']);
    Route::post('/delete/image',[JobsubmitController::class,'delete_image']);
    Route::post('/check-in',[JobsubmitController::class,'check_in']);

});
