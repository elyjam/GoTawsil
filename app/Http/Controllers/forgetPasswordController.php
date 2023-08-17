<?php

namespace App\Http\Controllers;

use App\User;
use Notification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\forgetPassword;
use Illuminate\Support\Facades\Validator;
class forgetPasswordController extends Controller
{
    //

    public function forgetPassword(Request $request)
    {

        if ($request->isMethod('post')) {


            $rules = [

                'email' => 'required|email|exists:users',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }{
                $token = Str::random(64);

                DB::table('password_resets')->insert(
                    ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
                );
                $user = User::where('email', $request->email)->first();

                // $user->notify(new forgetPassword());
                Notification::route('mail', $request->email)->notify(new forgetPassword($token));

                return redirect()->route('recoveryMessage');

            }


        }
        return view('front.password-forget');
    }

    public function getPassword(Request $request, $token)
    {
        if ($request->isMethod('post')) {
            $rules = [

                'password' => 'required|confirmed|min:8',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }{

            $updatePassword = DB::table('password_resets')
                ->where(['token' => $token])
                ->latest()->first();

            if (!$updatePassword) {

                return back()->withInput()->with('error', 'Invalid token!');
            }

            $user = User::where('email', $updatePassword->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();

            return redirect('/login')->with('message', 'Your password has been changed!');
        }
        }
        $updatePassword = DB::table('password_resets')
        ->where(['token' => $token])
        ->latest()->first();
        if($updatePassword != null){
            return view('front.getpassword',[
                'token' => $token
            ]);
        }else{
            return view('front.tokan-fail');
        }

    }

    public function recoveryMessage(){
        return view('front.recovery-message');
    }
}
