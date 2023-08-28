<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','update']]);
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            $credentials = $request->only('email', 'password');
            $user = User::where('email', $credentials['email'])->first();
    
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => (Object)[],
                ], 404);
            }
    
            $token = Auth::attempt($credentials);
    
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'data' => (Object)[],
                ], 401);
            }
           

            $authorization = [
                'token' => $token,
                'type' => 'bearer',
            ];
            session(['api_token' => $token]);
            $role = $user->role;
    
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Login!',
                'data' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
                'role' => $role,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'data' => (object)[],
            ], 500);
        }
    }
    

    
public function register(Request $request)
{
    try {
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
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Set nilai default 'User' jika 'role' tidak diisi
        $role = $request->input('role', 'User');

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'kelas' => $request->kelas,
            'dob' => $request->dob,
            'bio' => $request->bio,
            'phone_number' => $request->phone_number,
            'role' => $role,
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
    
        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dibuat',
            'data' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    } catch (\Exception $exception) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan selama registrasi',
            'data' => (object) [],
        ], 500);
    }
}

        
    
        
        
    
    public function update(Request $request)
    {
        $user = Auth::user();

    
       if (!$user || $user->role === 'User') {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Only Admin can update user information.',
            'data' => (object) [],
        ], 403);
    }
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
            'username' => 'sometimes|required|string|max:255',
            'kelas' => 'sometimes|required|string|max:11',
            'dob' => 'sometimes|required|date|max:255',
            'bio' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:14',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'data' => (object) [], // Empty object
            ], 422);
        }
    
        // Mengambil data dari request
        $userData = $request->only(['email', 'password', 'username', 'kelas', 'dob', 'bio', 'phone_number']);
    
        if (isset($userData['password'])) {
            // Jika password ada dalam data yang diperbarui, maka kita hash password baru
            $userData['password'] = Hash::make($userData['password']);
        }
    
        // Memperbarui informasi user
        $user->update($userData);
        
        $token = Auth::tokenById($user->id);

       
        return response()->json([
            'status' => 'success',
            'message' => 'User information updated successfully',
            'data' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }
    
  
    
  
    
    public function editProfile(Request $request)
    {
        $user = Auth::user();
    
        if ($user->role === 'User') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only Admin can edit profiles.',
            ], 403);
        }
    
        // Logic for editing profile (for Admin)
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

}