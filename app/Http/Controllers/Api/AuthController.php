<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dcat\Admin\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description:'')]
class AuthController extends Controller
{

    #[OA\Get(
        path: '/api/ping',
        operationId: 'ping',
        tags: ['Auth'],
        description: 'Ping',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'succes', type: 'string', example:'true')]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Unauthenticated')]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Forbidden')]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Internal server Error.')]
                )
            ),
        ]
    )
    ]
    public function ping()
    {
        return response()->json(['success' => true]);
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->only('username', 'name', 'email', 'password');
        $rules = [
            'username' => 'required|max:255|unique:admin_users',
            'name' => 'required|max:255|unique:admin_users',
            'email' => 'required|email|max:255|unique:admin_users',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }
        $username = $request->username;
        $name = $request->username;
        $email = $request->email;
        $password = $request->password;
        User::create(['username' => $username, 'name' => $name, 'email' => $email, 'password' => Hash::make($password)]);

        return $this->login($request);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'message' => 'We cant find an account with this credentials.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to login, please try again.'], 500);
        }
        return response()->json(['success' => true, 'token' => $token]);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        //\Cookie::forget('token');
        Admin::guard()->logout();
        JWTAuth::invalidate(JWTAuth::parseToken());

        return response()->json(['success' => true, 'message' => "You have successfully logged out."]);
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'message' => ['email' => $error_message]], 500);
        }
        try {
            //$token = Password::createToken($user);
            Password::sendResetLink($request->only('email'));
        } catch (Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'message' => $error_message], 500);
        }
        return response()->json([
            'success' => true, 'message' => 'A reset email has been sent! Please check your email.'
        ]);
    }

    public function details(Request $request)
    {
        return response()->json(['success' => true, 'user' => $request->user()]);
    }
}