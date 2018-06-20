<?php

namespace Anhi\Shared\Services;

use Auth, Validator;

use Request;

use October\Rain\Auth\AuthException;

use Hash;

use October\Rain\Auth\Models\User;

class AuthService
{
	private $throttle, $user;

	private function getThrottle ($credentials)
	{
		try {

			$ip = Request::ip();

			$this->throttle = Auth::findThrottleByLogin($credentials['email'], $ip);

		} catch (AuthException $ex) {

        }
	}

	function userExists ($email)
	{
		$this->user = User::where('email', $email)->first();

		return $this->user;
	}

	function auth ($credentials, $remember)
	{

		$this->getThrottle($credentials);

		try {

			//throw error if not found user
			if (!Hash::check($credentials['password'], $this->user->password))
			{
				$pass = md5($credentials['password']);

				$oldPass = md5($pass  . config('app.password_secret_key'));

				if ($oldPass !== $this->user->password)
					throw new AuthException("Incorrect Password");

				$this->user->password = $credentials['password'];
				$this->user->password_confirmation = $credentials['password'];
				$this->user->save();
			}

			if ($this->throttle)
			{
				
				$this->throttle->unsuspend();
	        	$this->throttle->clearLoginAttempts();
			}

	        $this->user->clearResetPassword();

	    	Auth::login($this->user, $remember);

	    	return true;

		} catch (AuthException $ex) {

        	if ($this->throttle)
	        	$this->throttle->addLoginAttempt();

        	return false;
        }
	}

	function checkThrottle ($credentials)
	{
		if ($this->throttle)
		{

	        //Check throttle to render captcha
			try {

				//this will throw error if user's login failed many times
				$this->throttle->check();

				return false;

			} catch (AuthException $ex) {

				return true;
			}
		}

		return true;
	}

	function validateUserInfo ($userInfo)
	{
		$rules = [
			'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
		];

		$validator = Validator::make($userInfo, $rules);

		return $validator;
	}

	function createAndLoginUser ($userInfo)
	{
		$validator = $this->validateUserInfo($userInfo);

		if ($validator->fails())
			return $validator;

		$user = Auth::register($userInfo, true);

		Auth::login($user);

		return true;
	}
}