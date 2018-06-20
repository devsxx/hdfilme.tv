<?php

namespace Anhi\WebFilm\Controllers;

use Backend\Classes\Controller;

use Anhi\Shared\Services\AuthService;

use Request, Redirect, Auth, Captcha, Input, Validator, Lang;

use Socialite, URL, Helper, Mail;

use RainLab\User\Models\User as UserModel;

use RainLab\User\Models\UserGroup;

class AuthController extends Controller
{
    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    function logout ()
    {
        Auth::logout();
        return Redirect::to('/');
    }

	function login ()
	{
        $credentials = [
            'email' => Request::input('email', ''),
            'password' => Request::input('password', '')
        ];

        $remember = Request::input('remember', false);

        $authService = new AuthService;

        $user = $authService->userExists($credentials['email']);

        if (!$user)
        {
            //Redirect to login with error: user not found
            return Redirect::to(Helper::mobilePrefix() . 'login')
                            ->with('error', ['msg' => 'User was not found.'])
                            ->with('email', $credentials['email']);
        }

        if (!$user->is_activated)
        {
            //Redirect to login with error: user not found
            return Redirect::to(Helper::mobilePrefix() . 'login')
                            ->with('error', ['msg' => 'User was been deactived.'])
                            ->with('email', $credentials['email']);
        }

        $login = $authService->auth($credentials, $remember);

        if ($login)
        {
            //redirect to home
            return Redirect::to(Helper::mobilePrefix());
            
        }

        $throttle = $authService->checkThrottle($credentials);

        return Redirect::to(Helper::mobilePrefix() . 'login')->with('error', [
                    'msg' => 'Email or Password do not match.',
                    'captcha' => $throttle
                ])
                ->with('email', $credentials['email']);
	}

    function getSocialRedirect ($provider)
    {
        session(['url.intended' => URL::previous()]);

        return Socialite::driver($provider)->redirect();
    }

    function getSocialHandle($provider)
    {
        try
        {
            $userInfo = Socialite::driver($provider)->user();
        }
        catch (\Exception $e) 
        {
            info('Login error:');
            info($e->getMessage());
        }

        if(empty($userInfo))
        {
            session(['url.intended' => URL::previous()]);
            return $this->getSocialRedirect($provider);
        }
        else
        {

            $user = Auth::findUserByLogin($userInfo->email);

            if($user)
            {
                if (!$user->is_activated)
                {
                    //Redirect to login with error: user not found
                    return Redirect::to(Helper::mobilePrefix() . 'login')
                                    ->with('error', ['msg' => 'User was been deactived.']);
                }

                Auth::login($user);
            }
            else
            {
                $pass = str_random();

                $user = Auth::register([
                    'email' => $userInfo->email,
                    'name' => $userInfo->name,
                    'password' => $pass,
                    'password_confirmation' => $pass
                ], true);

                $userGroupID = UserGroup::where('code', 'member')->first()->id;

                \DB::table('users_groups')->insert([
                    'user_id' => $user->id,
                    'user_group_id' => $userGroupID
                ]);

                Auth::login($user);
            }

            Helper::registerNewVIPSession();

            if ($back = session('url.intended'))
                return redirect($back);

            return redirect()->back();
        }
    }

    function register ()
    {
        $authService = new AuthService;

        $userInfo = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
            'name' => Input::get('name'),
        ];

        $result = $authService->createAndLoginUser($userInfo);

        if ($result !== true)
            return redirect()->back()->withErrors($result)->withInput();

        return redirect(Helper::mobilePrefix() . 'user');
    }

    function sendActivationLink ()
    {
        try {

            $rules = [
                'email' => 'required|email',
            ];

            $validation = Validator::make(post(), $rules);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            }

            $user = UserModel::where('email', post('email'))->first();

            if (empty($user))
                return redirect()->back()->withErrors([
                        'email' => trans('rainlab.user::lang.account.invalid_user')
                        ]);

            if ($user->is_activated) {
                return redirect()->back()->with([
                        'status' => Lang::get('rainlab.user::lang.account.already_active')
                        ]);
            }

            $this->sendActivationEmail($user);

            return redirect()->back()->with('status', 'We have e-mailed your activation link!');

        }
        catch (Exception $ex) {

            return redirect()->back()->with('status', 'Failed to send activation email. Please try again!');
        }
    }

    private function sendActivationEmail ($user)
    {
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        $link = url('/activation/confirm/' . $code);

        $data = [
            'name' => $user->name,
            'link' => $link
        ];

        Mail::send('rainlab.user::mail.activate', $data, function($message) use ($user) {

            $message->to($user->email, $user->name);
        });
    }

    public function active($code = null)
    {
        $actviedPage = Helper::mobilePrefix() . 'activation/confirmed';

        try {

            $code = post('code', $code);

            /*
             * Break up the code parts
             */
            $parts = explode('!', $code);
            if (count($parts) != 2) {
                return redirect($actviedPage)
                            ->with('message', Lang::get('rainlab.user::lang.account.invalid_activation_code'));
            }

            list($userId, $code) = $parts;

            if (!strlen(trim($userId)) || !($user = Auth::findUserById($userId))) {
                return redirect($actviedPage)
                            ->with(
                                'message', Lang::get('rainlab.user::lang.account.invalid_user'));
            }

            try {

                if (!$user->attemptActivation($code)) {
                    return redirect($actviedPage)
                                ->with('message', Lang::get('rainlab.user::lang.account.invalid_activation_code'));
                }
            } catch (\Exception $ex) {
                return redirect($actviedPage)
                                ->with('message', Lang::get('rainlab.user::lang.account.already_active'));
            }

            return redirect($actviedPage)->with('message', 'Your account successfully activated!');

        }
        catch (\Exception $ex) {
            
            return redirect($actviedPage)->with('message', 'Failed to active your account.');
        }
    }
}