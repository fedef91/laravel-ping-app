<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ResponseCode;
use App\Http\Resources\User as UserResource;

use Exception;

class AuthenticatedSessionController extends BaseController
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
            $user = Auth::user();
            $success['user_info'] = new UserResource($user);
            return $this->handleResponse("Login successfull", $success);
        }
        catch(Exception $e){
            return $this->handleError($e->getMessage(), $e->errors(), ResponseCode::INTERNAL_SERVER_ERROR);
        } 
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
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();//csrf
            return $this->handleResponse("Logout successfull");
        }
        catch(Exception $e){
            return $this->handleError($e->getMessage(), $e->errors(), ResponseCode::INTERNAL_SERVER_ERROR);
        }
    }
}
