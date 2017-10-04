<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Contracts\Services\PassportInterface;
use App\Http\Controllers\Api\ApiController;
use App\Exceptions\Api\NotFoundErrorException;
use App\Http\Requests\Api\Auth\LoginRequest;

class LoginController extends ApiController
{
    use AuthenticatesUsers;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(LoginRequest $request, PassportInterface $service)
    {
        $data = $request->only(['email', 'password']);

        if ($this->attemptLogin($request)) {
            $response = $request->has('refresh_token') ? $service->refreshGrantToken($request->refresh_token)
                : $service->passwordGrantToken($data);

            if (isset($response->error)) {
                throw new NotFoundErrorException($response->message, 404);
            } elseif (isset($response->access_token)) {
                $this->compacts['passport'] = $response;
            }
        } else {
            throw new NotFoundErrorException(__('auth.failed'), 401);
        }

        return $this->jsonRender();
    }
}
