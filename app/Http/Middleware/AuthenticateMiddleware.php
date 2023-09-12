<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthenticateMiddleware
{
    public function handle($request, Closure $next)
    {
        // Jika ini adalah permintaan login atau register, lanjutkan tanpa memeriksa otentikasi.
        if ($this->isLoginOrRegisterRequest($request)) {
            return $next($request);
        }

        // Jika ini adalah permintaan pembaruan, lakukan validasi dan otorisasi.
        if ($this->isUpdateRequest($request)) {
            try {
                $userToUpdate = $this->getUserToUpdate($request);

                // Validasi data yang akan diupdate.
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

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors(),
                        'data' => (object)[],
                    ], 422);
                }
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak ditemukan',
                    'data' => (object)[],
                ], 404);
            }
        }

        return $next($request);
    }

    protected function isUpdateRequest($request)
    {
        return $request->isMethod('put') && $request->routeIs('update');
    }

    protected function isLoginOrRegisterRequest($request)
    {
        return $request->is('api/login') || $request->is('api/register');
    }

    protected function getUserToUpdate($request)
    {
        // Implementasi untuk mendapatkan pengguna yang akan diupdate
        // Anda bisa menggunakan ID pengguna yang diperoleh dari rute.
        $id = $request->route('id');
        return User::findOrFail($id);
    }
}
