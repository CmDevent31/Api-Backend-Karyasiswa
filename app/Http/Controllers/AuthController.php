<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','update','logout']]);
    }

    public function login(Request $request)
    {
        try {
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
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal server',
                'data' => (object)[],
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
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
            $user = new User();
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->profile_image = null;
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

                Storage::disk('public')->put($imagePath, file_get_contents($image));

                $user->profile_image = url(Storage::url($imagePath));
            }

            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil dibuat',
                'data' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal server',
                'data' => (object)[],
            ], 500);
        }
    }
public function update(Request $request, $id)
{
    try {
        // 1. Pastikan parameter $id valid
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID pengguna tidak valid',
                'data' => (object)[],
            ], 400);
        }

        // 2. Mengambil pengguna yang sedang diautentikasi
        $authenticatedUser = Auth::users();

        // 3. Pastikan pengguna terotentikasi dengan benar
        if (!$authenticatedUser || !$authenticatedUser->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak terotentikasi dengan benar',
                'data' => (object)[],
            ], 401);
        }

        // 4. Mencari pengguna berdasarkan ID
        $userToUpdate = User::find($id);

        // 5. Pastikan pengguna yang ditemukan
        if (!$userToUpdate) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan',
                'data' => (object)[],
            ], 404);
        }

        // 6. Memeriksa apakah pengguna yang diautentikasi adalah pemilik data yang akan diperbarui
        if ($authenticatedUser->id !== $userToUpdate->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengakses pengguna ini',
                'data' => (object)[],
            ], 403);
        }

        // 7. Validasi data yang dikirim dalam permintaan
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $userToUpdate->id,
            'password' => 'sometimes|required|min:6',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'username' => 'sometimes|required|max:255',
            'kelas' => 'sometimes|required|max:20',
            'dob' => 'sometimes|required|max:255',
            'bio' => 'sometimes|required|max:255',
            'phone_number' => 'sometimes|required|max:14',
        ]);

        // 8. Jika validasi gagal, kembalikan respons dengan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                'data' => (object)[],
            ], 422);
        }

        // 9. Memeriksa dan mengupdate data sesuai dengan permintaan
        if ($request->has('email')) {
            $userToUpdate->email = $request->input('email');
        }
        if ($request->has('password')) {
            $userToUpdate->password = Hash::make($request->input('password'));
        }
        if ($request->has('username')) {
            $userToUpdate->username = $request->input('username');
        }
        if ($request->has('kelas')) {
            $userToUpdate->kelas = $request->input('kelas');
        }
        if ($request->has('dob')) {
            $userToUpdate->dob = $request->input('dob');
        }
        if ($request->hasFile('profile_image')) {
            // 10. Validasi dan simpan file gambar (gantilah dengan kode validasi dan penyimpanan yang sesuai)
            // ...

            // 11. Setelah validasi, simpan URL gambar dalam $userToUpdate->profile_image
        }
        if ($request->has('bio')) {
            $userToUpdate->bio = $request->input('bio');
        }
        if ($request->has('phone_number')) {
            $userToUpdate->phone_number = $request->input('phone_number');
        }

        // 12. Simpan perubahan pada data pengguna
        $userToUpdate->save();

        // 13. Kembalikan respons berhasil
        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil diperbarui',
            'data' => $userToUpdate,
        ], 200);
    } catch (\Exception $e) {
        // 14. Tangani kesalahan internal server dengan respons 500
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan internal server: ' . $e->getMessage(),
            'data' => (object)[],
        ], 500);
    }
}

    

    public function getUserInfo()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'data' => (object)[],
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

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
