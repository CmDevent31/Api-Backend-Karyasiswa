<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
{
    // Your authentication logic goes here
    // You can use $guards to handle different authentication guards if needed

    if (!auth()->check()) {
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

            // Define validation rules for update request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'required|min:6',
                'username' => 'required|max:255',
                'kelas' => '|required|max:20',
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

    return $next($request);
}

/**
 * Check if the request is an update request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return bool
 */
protected function isUpdateRequest(Request $request): bool
{
    // Implement your logic to determine if it's an update request
    // For example, check the request method, route, or parameters
    return $request->isMethod('post') && $request->routeIs('your.update.route');
}
}