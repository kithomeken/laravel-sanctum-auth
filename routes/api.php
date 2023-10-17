<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController as AuthController;

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

# Sanctum Authentication Routes
Route::group(['prefix' => '/v1/account/auth', 'middleware' => ['cors']], function() {
    # Account Sign In
    Route::post(
        '/sign-in', 
        [AuthController::class, 'authenticationSignIn'],
    );

    # Authenticated Routes
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post(
            '/0-token/invalidate', 
            [AuthController::class, 'sanctumAuthSignOut'],
        );
        
        Route::get(
            '/profile', 
            [AuthController::class, 'accountProfile'],
        );

        /* # Email Functionalities
        Route::group(['prefix' => '/email'], function() {
            Route::get(
                '/history', 
                [AuthController::class, 'accountEmailHistory'],
            );
        
            Route::post(
                '/change', 
                [AuthController::class, 'changeAccountEmail'],
            );
        
            Route::post(
                '/undo-change', 
                [AuthController::class, 'undoChangeAccountEmail'],
            );
        
            Route::post(
                '/resend-verification', 
                [AuthController::class, 'resendAccountVerificationMail'],
            );
        });

        # Password configurations
        Route::group(['prefix' => '/password'], function() {
            Route::post(
                '/change', 
                [AuthController::class, 'changeAccountPassword'],
            );
        });

        # Account Preferences
        Route::group(['prefix' => '/preferences'], function() {
            Route::get(
                '/', 
                [AuthController::class, 'getAccountPreferences'],
            );
            
            Route::post(
                '/timezone/set', 
                [AuthController::class, 'setAccountTimezonePreference'],
            );
        });

        # Auth team rights
        Route::group(['prefix' => '/team'], function() {
            Route::get(
                '/rights', 
                [AuthController::class, 'accountAuthTeamRights'],
            );
        }); */
    });
});