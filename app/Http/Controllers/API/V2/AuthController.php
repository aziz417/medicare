<?php

namespace App\Http\Controllers\API\V2;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Authentication Guard
     * @var string
     */
    protected $guard = 'jwt';

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth:{$this->guard}", ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $username = filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $credentials = [
            $username => $request->email,
            'password' => $request->password,
            'status' => 'active'
        ];


        if (! $token = auth($this->guard)->attempt($credentials)) {
            throw new ApiException('Invalid Credentials!', 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return new ApiResponse(auth($this->guard)->user()->getPublicData(['meta']));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();
        return new ApiResponse(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }
}
