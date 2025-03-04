<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create user but set email_verified_at as null
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification
        event(new Registered($user));

        // Redirect to success page
        return response()->json([
            'success' => true,
            'redirect' => route('verification.notice', ['email' => $user->email])
        ]);
    }

    public function verifiedEmail($id, $hash){
        $user = User::find($id);
        if(empty($user)){
            return redirect('login')->with('error', 'The email is not validated correctly');
        }
        $date = new \DateTime();

        $user->is_verify = 1;
        $user->email_verified_at = $date->format('Y-m-d H:i:s');
        $user->save();

        return redirect('login')->with('success', 'The email is validated correctly');
    }
}
