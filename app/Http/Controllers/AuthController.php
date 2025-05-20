<?php
 
namespace App\Http\Controllers;
 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

 
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'dob'       => 'nullable|date',
            'anniversary_date' => 'nullable|date',
        
        ]);
 
        $user = User::create([
            'title'     => $request->title,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'dob'       => $request->birth_date,
            'anniversary_date' =>$request->anniversary_date,
            'user_type' => 'user',
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

        if (! $user || ! Hash::check($request->password, $user->password)) {
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
 
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }
 
    // public function sendResetLink(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);
 
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );
 
    //     if ($status === Password::RESET_LINK_SENT) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => trans($status),
    //         ]);
    //     }
 
    //     return response()->json([
    //         'success' => false,
    //         'message' => __($status),
    //     ], 422);
    // }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // For API/React
        if ($request->expectsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => trans($status)]);
            }
            return response()->json(['message' => trans($status)], 500);
        }

        // For Laravel Blade
        return back()->with('status', trans($status));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successful.']);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }


 
    public function showResetForm(Request $request)
    {
        return response()->json([
            'success' => true,
            'token'   => $request->query('token'),
            'email'   => $request->query('email'),
        ]);
    }
 
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
 
        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
 
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => trans($status),
            ]);
        }
 
        return response()->json([
            'success' => false,
            'message' => trans($status),
        ], 500);
    }

    // public function forgetPassword(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed|min:6',
    //     ]);

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->password = Hash::make($password);
    //             $user->save();
    //         }
    //     );

    //     if ($status === Password::PASSWORD_RESET) {
    //         return response()->json(['message' => trans($status)]);
    //     }

    //     return response()->json(['message' => trans($status)], 500);
    // }
}