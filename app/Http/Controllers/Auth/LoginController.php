<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!empty($user) && $user->is_verify == 0){
            return response()->json(['success' => false, 'message' => 'The account is not verify. Please review your the verification email I send you']);
        }

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials, $request->remember)){
            return response()->json(['success' => true, 'redirect' => '/dashboard']);
        }

        return response()->json(['success' => false, 'error' => ['email' => 'Invalid Credentials']]);

    }

    public function logout(){
        Auth::logout();
        return redirect('login')->with('success', 'Logged of successfully');
    }




}
