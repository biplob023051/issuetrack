<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;

class AuthController extends Controller
{
    public function __construct()
	{
	    $this->middleware('auth:api')->only('logout');
	}

	// Method for registration
	public function register() {
		$this->validate(request(), [
			'name' => 'required|max:255',
			'email' => 'required|email|unique:users',
			'password' => 'required|between:6,25|confirmed'
		]);
		
		User::create([
			'name' => request('name'),
			'email' => request('email'),
			'password' => bcrypt(request('password')),
		]);

		return response()
		->json(['success' => true]);
	}

	// method for login
	public function login() {
		$this->validate(request(), [
			'email' => 'required|email',
			'password' => 'required'
		]);
		$user = User::where('email', request('email'))->first();
		if ($user && Hash::check(request('password'), $user->password)) {
			// generate new api token
            $user->api_token = str_random(60);
            $user->save();

            return response()
                ->json([
                    'success' => true,
                    'api_token' => $user->api_token,
                    'user_id' => $user->id
                ]);
		}
		return response()
			->json(['password' => ['Email and password does not match']], 422);
		
	}

	public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()
            ->json([
                'success' => true
            ]);
    }
}
