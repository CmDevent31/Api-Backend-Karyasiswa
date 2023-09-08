<?php

namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User; // Pastikan namespace yang sesuai
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'update']]);
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
                'message' => 'Email tidak ditemukan',
                'data' => [],
            ], 401);
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah',
                'data' => [],
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
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            'username' => 'required|string|max:255|unique:users',
            'kelas' => 'required|string|max:11',
            'gender' => 'required|in:Pria,Wanita',
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
        $user = new User(); // Ganti dari 'create' menjadi inisialisasi objek User
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->profile_image = null; // Default value
        $user->username = $request->input('username');
        $user->kelas = $request->input('kelas');
        $user->gender = $request->input('gender');
        $user->dob = $request->input('dob');
        $user->bio = $request->input('bio');
        $user->phone_number = $request->input('phone_number');
        $user->role = $role;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();

            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));

            $user->profile_image = url(Storage::url($imagePath)); // Perbaiki variabel yang salah
        }

        $user->save();

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

    // Fungsi untuk mengupdate informasi pengguna
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
    
            // Validasi aturan yang bersifat opsional jika ada data dalam permintaan
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|min:6',
                'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
                'username' => 'sometimes|required|max:255',
                'kelas' => 'sometimes|required|max:20',
                'dob' => 'sometimes|required|max:255',
                'bio' => 'sometimes|required|max:255',
                'phone_number' => 'sometimes|required|max:14',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'data' => (object)[],
                ], 422);
            }
    
            // Update user data based on the request
            if ($request->has('email')) {
                $user->email = $request->input('email');
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            if ($request->has('username')) {
                $user->username = $request->input('username');
            }
            if ($request->has('kelas')) {
                $user->kelas = $request->input('kelas');
            }
            if ($request->has('dob')) {
                $user->dob = $request->input('dob');
            }
            if ($request->hasFile('profile_image')) {
                // Handle profile image update here
                // ...
            }
            if ($request->has('bio')) {
                $user->bio = $request->input('bio');
            }
            if ($request->has('phone_number')) {
                $user->phone_number = $request->input('phone_number');
            }
    
            // Save the updated user's data
            $user->save();
    
            return response()->json([
                'success' => true,
                'message' => 'User information updated successfully',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'data' => (object)[],
            ], 500);
        }
    }
    


    // Fungsi untuk mendapatkan informasi pengguna yang terautentikasi
    public function GetUserInfo()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    // Fungsi untuk logout
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'data' => (object)[],
        ]);
    }

    // Fungsi untuk refresh token
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
