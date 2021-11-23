<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\UserContract;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Enums\ResponseCode;

use Illuminate\Database\QueryException;
use Exception;

class RegisteredUserController extends Controller
{
    protected $userRepository;
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(UserContract $userRepository){
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  App\Http\Requests\Auth\RegisterRequest  $request
     * @return JSON
     *
     * @throws Illuminate\Database\QueryException
     */
    public function store(RegisterRequest $params)
    {
        try{
            $user = $this->userRepository->create($params);
            event(new Registered($user));
            Auth::attempt($params->only('email', 'password'));
            $success['token'] = $user->createToken('laravel-token')->accessToken;
            $response = ['success' => $success];
            $code = ResponseCode::SUCCESS;
        }
        catch(QueryException $e){
            $response = ['error' => $e->getMessage()];
            $code = ResponseCode::INTERNAL_SERVER_ERROR;
        }
        return response()->json($response, $code);
    }
}
