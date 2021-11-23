<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ResponseCode;

use Exception;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return JSON
     */
    public function store(LoginRequest $request)
    {
        try{
            $request->authenticate();
            $success['token'] = Auth::user()->createToken('laravel-token')->accessToken;
            $response = ['success' => $success];
            $code = ResponseCode::SUCCESS;
        }
        catch(Exception $e){
            $response = ['errors' => $e->errors()];
            $code = ResponseCode::INTERNAL_SERVER_ERROR;
        }
        return response()->json($response, $code);
        
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON
     */
    public function destroy(Request $request)
    {
        try{
            $token = Auth::user()->token();
            $token->delete();
            Auth::guard('web')->logout();
            $response = "";
            $code = ResponseCode::SUCCESS;
        }
        catch(Exception $e){
            $response = ['error' => $e->getMessage()];
            $code = ResponseCode::INTERNAL_SERVER_ERROR;
        }
        return response()->json($response , $code);
    }
}
