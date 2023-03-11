<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    //
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {
        try {
            //code...
            if ($this->isAPI()) {
                $validator = $request->validate([
                    'email' => 'required|email|exists:users',
                ]);

                $token = Str::random(64);

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                dispatch(new SendMailJob($token, $request->email));
                // Mail::send('mail.forgetPassword', ['token' => $token], function ($message) use ($request) {
                //     $message->to($request->email);
                //     $message->subject('Reset Password');
                // });

                $data = 'Kami telah mengirimkan reset kata sandi di email Anda. Silahkan cek email Anda!';
                return $this->success($data);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('sso.forgotPasswordLink', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();
        // dd($request->token);
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $updatePassword->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();

        return redirect()->route('reset.password.info');
    }

    public function resetPasswordInfo()
    {
        return view('sso.forgotPasswordBlank');
    }
}
