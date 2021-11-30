<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Contracts\UserContract;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\Auth\RegisterRequest;


use Illuminate\Database\QueryException;
use Exception;

class RegisteredUserController extends BaseController
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
    public function store(RegisterRequest $request)
    {
        try{
            $user = $this->userRepository->create($request->validated());
            Auth::login($user);
            event(new Registered($user));
            $success['user_info'] = new UserResource($user);
            return $this->handleResponse("Registration successfull", $success);
        }
        catch(QueryException $e){
            return $this->handleError($e->getMessage(),ResponseCode::INTERNAL_SERVER_ERROR);
        }
    } 
}
