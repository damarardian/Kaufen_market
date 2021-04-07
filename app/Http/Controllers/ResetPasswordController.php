<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{  
    public function forgot() {
        $credentials = request()->validate(['email' => 'required|email']);

        Password::sendResetLink($credentials);

        return response()->json(["message" => "Reset Password LINK Has Been Sent To Your Emal"], 200);
    }

    public function reset(ResetPasswordRequest $request) {
        $reset_password_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["error" => "Invalid TOken", 255]);
        }

        // return response()->json(["success" => "Your Password Has Been Changed"], 200);
        return view('succesforgot');
    }
    
}



// public function linkReset (Request $request)
//     {
//         $request->validate(['email' => 'required|email']);
//         $status = Password::sendResetLink(
//             $request->only('email')
//         );
//         return $status === Password::RESET_LINK_SENT
//                     ?  response()->json(["message" => __($status)], 200) 
//                     : response()->json(["error" => __($status)], 400);
//     }


//     public function formReset($token) 
//     {
//         $email = $_GET['email'];
//         return view('auth.password-reset', ['token' => $token, 'email' => $email]);
//     }

//     /**
//      * For Reset Password
//      */
//     public function resetPass (Request $request) {
//     $request->validate([
//         'token' => 'required',
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function ($user, $password) use ($request) {
//             $user->forceFill([
//                 'password' => Hash::make($password)
//             ])->save();

//             $user->setRememberToken(Str::random(60));

//             event(new PasswordReset($user));
//         }
//     );

//     return $status == Password::PASSWORD_RESET
//                 ?view("cekforgot", ['status' => __($status)]) 
//                 : view("cekforgot", ['status' => __($status)]);
//     }