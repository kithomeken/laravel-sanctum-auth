<?php

namespace App\Http\Controllers\auth;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ApiErrorHandler as ApiErrorHandler;

class AuthController extends Controller
{
    public function authenticationSignIn(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email'     => 'required|string|email|max:255',
                'password'  => 'required|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    "success"   => false,
                    "error"     => array(
                        "code"      => 400,
                        "message"   => $validator->errors(),
                    ),
                ]);
            }
    
            $credentials = $request->only('email', 'password');
    
            if (Auth::attempt($credentials)) {
                $authenticatedUser = Auth::user();
                $accountId =  $authenticatedUser->uuid;
                $sanctumToken =  $authenticatedUser->createToken('BESKA_TOKEN_')->plainTextToken;
    
                return response()->json([
                    "success"   => true,
                    "payload"   => array(
                        "token"     => $sanctumToken,
                        "uuid"      => $accountId,
                    ),
                ]);
            } else {
                $response = [
                    "success"   => false,
                    "error"     => array(
                        "code"      => 401,
                        'message'   => "Failed. Credentials do not match our records.",
                    ),
                ];
                
                return response()->json($response);
            }
        } catch (\Throwable $th) {
            $throwExcecption = new ApiErrorHandler(500);
            $throwExcecption->throwException();
        }
    }

    public function sanctumAuthSignOut(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "success"   => true,
            'message' => "Authenticated user signed out",
        ]);
    }

    public function accountProfile(Request $request) {
        $accountUuid = $request->user()->uuid;

        $accountDetails = User::select('first_name', 'last_name', 'account_type', 'active', 'expires_at')
        ->where('uuid', $accountUuid)
        ->first();

        $response = [
            'success' => true,
            'payload' => array(
                'account'   => $accountDetails,
            ),
        ];
        
        return response()->json($response);
    }
}
