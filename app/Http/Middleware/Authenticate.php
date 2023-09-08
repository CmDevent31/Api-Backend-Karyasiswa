<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        // Logika autentikasi Anda berada di sini
        // Anda dapat menggunakan $guards untuk menangani penjagaan autentikasi yang berbeda jika diperlukan

        if (!Auth::check()) {
            return $this->redirectTo($request);
        }

        // Check if it's an update request and process it
        if ($this->isUpdateRequest($request)) {
            try {
                // Find the user by ID
                $id = $request->route('id'); // Assuming the route parameter is named 'id'
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
                    'email' => 'sometimes|required|email|max:255|unique:users,email,' . Auth::user()->id,
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

                return $next($request);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred',
                    'data' => (object)[],
                ], 500);
            }
        }

        return $next($request);
    }

    protected function redirectTo(Request $request)
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function isUpdateRequest(Request $request)
{
    // Implementasikan logika Anda untuk menentukan apakah ini adalah permintaan pembaruan
    // Berdasarkan rute atau jenis permintaan
    return $request->isMethod('post') && $request->routeIs('update-profile'); // Sesuaikan dengan nama rute Anda
}

    
}
