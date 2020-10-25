<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\PersonalAccessTokenResult;

class ApiAuthController extends Controller
{
    /**
     * @param RegisterUserRequest $request
     * @return Response
     */
    public function register(RegisterUserRequest $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        /** @var User $user */
        $user = User::create($request->toArray());
        $result = $user->createToken('Laravel Password Grant Client');

        return $this->respondWithToken($result);
    }

    /**
     * @param LoginUserRequest $request
     * @return Response
     */
    public function login(LoginUserRequest $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $result = $user->createToken('Laravel Password Grant Client');

            return $this->respondWithToken($result);
        }
        $response = ['message' => 'user credentials is wrong'];

        return response($response, 422);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }

    /**
     * Get the token array structure.
     *
     * @param PersonalAccessTokenResult $token
     * @return Response
     */
    protected function respondWithToken(PersonalAccessTokenResult $token)
    {
        return response([
            'token_type' => 'Bearer',
            'access_token' => $token->accessToken,
            'expires_in' => now()->diffInSeconds($token->token->expires_at),
        ]);
    }
}
