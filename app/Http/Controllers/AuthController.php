<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

   // Fungsi untuk login
public function login(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // Cek kredensial dan buat token
    $credentials = $request->only('email', 'password');
    $user = User::where('email', $request->email)->first(); // Cek apakah email ditemukan

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email tidak ditemukan'
        ], 401);
    }

    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Password salah'
        ], 401);
    }

    // Mengambil data pengguna yang terautentikasi
    $user = auth()->user();

    return response()->json([
        'status' => 'success',
        'message' => 'Login berhasil',
        'data' => $user,
        'authorisation' => [
            'token' => $token,
            'type' => 'bearer'
        ]
    ]);
}


    // Fungsi untuk mendaftarkan pengguna baru
public function register(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'username' => 'required|string|max:255|unique:users',
        'kelas' => 'required|string|max:11',
        'dob' => 'required|date|max:255',
        'bio' => 'required|string|max:255',
        'phone_number' => 'required|string|max:14',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 400);
    }

    // Membuat pengguna baru
    $role = $request->input('role', 'User');
    $user = User::create(array_merge(
        $validator->validated(),
        ['password' => bcrypt($request->password)],
        ['role' => $role]
    ));

    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'status' => 'success',
        'message' => 'Pengguna berhasil dibuat',
        'data' => $user,
        'authorisation' => [
            'token' => $token,
            'type' => 'bearer'
        ]
    ]);
}


    // ... (Fungsi lainnya seperti logout, refresh, update, userProfile)

    // Fungsi untuk menghasilkan respons dengan token
    protected function createNewToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
