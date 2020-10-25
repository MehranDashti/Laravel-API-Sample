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
     * @OA\Post(
     *      path="/register",
     *      summary="Register",
     *      description="Register by name, email, password",
     *      operationId="authRegister",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="user2"),
     *              @OA\Property(property="email", type="string", format="email", example="user2@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="token_type", type="string", example="Bearer"),
     *              @OA\Property(property="access_token", type="string"),
     *              @OA\Property(property="expires_in", type="integer", example=86398),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="user credentials is wrong"),
     *              @OA\Property(property="errors", type="json"),
     *          )
     *      )
     * )
     */
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
     * @OA\Post(
     *      path="/login",
     *      summary="Sign in",
     *      description="Login by email, password",
     *      operationId="authLogin",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="token_type", type="string", example="Bearer"),
     *              @OA\Property(property="access_token", type="string"),
     *              @OA\Property(property="expires_in", type="integer", example=86398),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="user credentials is wrong")
     *          )
     *      )
     * )
     */
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
     * @OA\Post(
     *      path="/logout",
     *      summary="Logout",
     *      description="Logout user and invalidate token",
     *      operationId="authLogout",
     *      tags={"Auth"},
     *      security={ {"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="You have been successfully logged out!"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Returns when user is not authenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *          )
     *       )
     * )
     */
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
