<?php

namespace App\Http\Controllers\Auth;

use App\Enum\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $req)
    {
        // ================================================>> Data Validation
        $this->validate(
            $req,
            [
                'username' => ['required'],
                'password' => 'required|min:6|max:20'
            ],
            [
                'username.required' => 'សូមបញ្ចូលអុីម៉ែលឬលេខទូរស័ព្ទ',
                'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់',
                'password.min'      => 'លេខសម្ងាត់ត្រូវធំជាងឬស្មើ៦',
                'password.max'      => 'លេខសម្ងាត់ត្រូវតូចជាងឬស្មើ២០',
            ]
        );

        // =================>> Role must != Customer && isActive = 1
        $user = User::where('phone', $req->username)->where('is_active', 1)->first();
        if (!$user || $user->role_id == RoleEnum::CUSTOMER) {
            return response([
                'status'  => 'error',
                'message' => 'ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // ================================================>> Check Login
        $credentials = array(
            'phone'             =>  $req->username,
            'password'          =>  $req->password
        );

        try {
            JWTAuth::factory()->setTTL(10080); //10080 នាទី => 7day
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response([
                    'status'    => 'error',
                    'message'   => 'ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response([
                'status'    => 'បរាជ័យ',
                'message'   => 'មិនអាចបង្កើតនិមិត្តសញ្ញាទេ',
                'error'     => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // ================================================>> Prepare Response Data
        $user = auth()->user();
        $dataUser = [
            'id'        => $user->id,
            'name'      => $user->name,
            'phone'     => $user->phone,
            'email'     => $user->email,
            'avatar'    => $user->avatar,
            'role'      => $user->role->name
        ];

        // ================================================>> Response Back to Client
        $expires_in = JWTAuth::factory()->getTTL() >= 1440 ? (JWTAuth::factory()->getTTL() / 60) / 24 . ' days' : JWTAuth::factory()->getTTL() / 60 . ' hours';
        return response([
            'status'    => 'success',
            'data'      => [
                'access_token'  => $token,
                'expires_in'    => $expires_in,
                'user'          => $dataUser,
            ]
        ], Response::HTTP_OK);
    }

    public function register(Request $req)
    {
        return $req->all();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response([
            'status'    => 'success',
            'message'   => 'Successfully logged out'
        ], Response::HTTP_OK);
    }
}
