<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Mail\AdminsResetPassword;
use Carbon\Carbon;
use Mail;
use App\Admins;
use DB;
use Illuminate\Http\Request;

class AdminAuth extends Controller
{
    /*
    * Login Function View 
    */

    public function login()
    {
        if(admin()){
            return redirect(adminUrl());
        }
        return view('admin.auth.login');
    }

    public function doLogin()
    {
        if(admin()){
            return redirect(adminUrl());
        }
        $email = request('email');
        $password = request('password');

        $rememberme = request('rememberme') ? true : false;
            
        if(auth()->guard('admins')->attempt(
            ['email' => $email, 
            'password' => $password ],
            $rememberme))
        {
            return redirect(adminUrl());
        }else{
            session()->flash('error', 'Email or Password Incorrect Please try again');
            return redirect(adminUrl('login'))->withInput();
        }
    }

    public function logout()
    {
        auth()->guard('admins')->logout();
        session()->flash('success', 'You Are Logout From Dashboard ..');
        return redirect('admin/logout'); 
    }

    public function resetPassword()
    {
        if(admin()){
            return redirect(adminUrl());
        }
        return view('admin.auth.resetpassword');
    }

    public function forgot_password_post() {
		$admin = Admins::where('email', request('email'))->first();
		if (!empty($admin)) {
			$token = app('auth.password.broker')->createToken($admin);
			$data  = DB::table('password_resets')->insert([
					'email'      => $admin->email,
					'token'      => $token,
					'created_at' => Carbon::now(),
				]);
			Mail::to($admin->email)->send(new AdminsResetPassword(
                ['data' => $admin, 'token' => $token]));
			session()->flash('success', 'Reset Link is Sent');
			return back();
        }
        session()->flash('error', 'This Email Not Exists');

		return back();
	}

	public function reset_password_final($token) {

		$this->validate(request(), [
				'password'              => 'required|confirmed',
				'password_confirmation' => 'required',
			], [], [
				'password'              => 'Password',
				'password_confirmation' => 'Confirmation Password',
			]);

		$check_token = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHours(2))->first();
		if (!empty($check_token)) {
			Admins::where('email', $check_token->email)->update([
					'email'    => $check_token->email,
					'password' => bcrypt(request('password'))
				]);
			DB::table('password_resets')->where('email', $check_token->email)->delete();
			adminGurd()->attempt(['email' => $check_token->email, 'password' => request('password')], true);
			return redirect(adminUrl());
		} else {
			return redirect(adminUrl('reset/password'));
		}
	}

	public function reset_password($token) {
		$check_token = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHours(2))->first();
		if (!empty($check_token)) {
			return view('admin.auth.reset_password', ['data' => $check_token]);
		} else {
			return redirect(adminUrl('reset/password'));
		}
	}
}
