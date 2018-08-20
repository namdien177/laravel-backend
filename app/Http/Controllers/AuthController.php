<?php

namespace App\Http\Controllers;

use App\author;
use App\Http\Requests\SignupAuthor;
use App\Http\Requests\SignupRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login','signupAuthor','signupViewer']]);
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login()
	{
		$credentials = request(['email', 'password']);

		if (! $token = auth()->attempt($credentials)) {
			return response()->json(['error' => 'Email or Password is Incorrect!'], 401);
		}

		return $this->respondWithToken($token);
	}

	public function signupAuthor(SignupAuthor $request)
	{
		$user = User::create($request->all(['name','email','password','authorize']));
		$newauthor = new author;
		$newauthor->name = $request->authorname;
		$newauthor->information = $request->information;
		$newauthor->idViewer = $user->id;
		$newauthor->save();
		return $this->login($request);
	}

	public function signupViewer(SignupRequest $request){
		$user = User::create($request->all(['name','email','password','authorize']));
		return $this->login($user);
	}

	public function signupUser(Request $request){
//		return $request->all('name');
		$user = User::create($request->all());
		return $this->login($user);
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		return response()->json(auth()->user());
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		return $this->respondWithToken(auth()->refresh());
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60,
			'user'      => auth()->user()
		]);
	}
}
