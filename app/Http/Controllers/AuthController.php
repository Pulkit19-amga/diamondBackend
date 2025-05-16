<?php
 
namespace App\Http\Controllers;
 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'user_type' => 'required|in:user,admin,vendor',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'user_type' => $request->user_type,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Registration successful.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user_type'    => $user->user_type,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
 
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials'],
            ]);
        }
 
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'user'    => $user,
            'token'   => $token,
        ]);
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required'
        ]);

        $errors = [];

        // If initial validator fails, collect those errors
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
        }

        // Custom check: current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            $errors['current_password'][] = 'Current password is incorrect.';
        }

        // Custom check: password match
        if ($request->new_password !== $request->new_password_confirmation) {
            $errors['new_password'][] = 'The new password confirmation does not match.';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $errors
            ], 422);
        }

        // Update password
        $request->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.'
        ]);
    }
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => trans($status)]);
        }

        return response()->json(['message' => trans($status)], 500);
    }

    public function showResetForm(Request $request)
    {
        return response()->json([
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => trans($status)]);
        }

        return response()->json(['message' => trans($status)], 500);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }
 
    
}