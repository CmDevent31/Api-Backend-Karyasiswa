<?php

namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User; // Pastikan namespace yang sesuai

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','update']]);
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


public function update(Request $request, $id)
{
    try {
        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => (object)[],
            ], 404);
        }

        // Define validation rules for update request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'required|min:6',
            'username' => 'required|max:255',
            'kelas' => 'required|max:11',
            'dob' => 'required|max:255',
            'bio' => 'required|max:255',
            'phone_number' => 'required|max:14',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'data' => (object)[],
            ], 422);
        }

        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->username = $request->input('username');
        $user->kelas = $request->input('kelas');
        $user->dob = $request->input('dob');
        $user->bio = $request->input('bio');
        $user->phone_number = $request->input('phone_number');

        // Save the updated user's data
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User information updated successfully',
            'data' => $user,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred',
            'data' => (object)[],
        ], 500);
    }
}





    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'data'    => (Object)[],
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

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
