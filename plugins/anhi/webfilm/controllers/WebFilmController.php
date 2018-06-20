<?php

namespace Anhi\WebFilm\Controllers;

use Backend\Classes\Controller;

use Input, Cache, Auth, Request, Validator, Mail, Helper;

use Anhi\WebFilm\Services\FilmService;

use Anhi\Movie\Models\MovieEpisodeWatched;

use Anhi\Shared\Services\MovieService;

use Anhi\WebFilm\Services\ContactService;

use Anhi\WebFilm\Services\SitemapService;

use RainLab\User\Models\User as UserModel;

class WebFilmController extends Controller
{
	public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    function generateSitemapIndex ()
    {
        return \Response::view('anhi.webfilm::sitemap')->header('Content-Type', 'text/xml');
    }

    function generateSitemapChild ($type)
    {
        //cats/{name}/{id}
        //movie-director/{id}
        //movie-actor/{id}

        $typeInfo = explode('_', $type);
        $mobile = '';

        if (count($typeInfo) == 2)
        {
            $type = $typeInfo[1];
            $mobile = 'mobile';
        }
        else 
            $type = $typeInfo[0];

        $view = Cache::remember(

            config('cachekeys.view.sitemap') . $type . $mobile,

            config('cache.sitemap_view_expire'),

            function () use ($type, $mobile) {

                $movieService = new MovieService;

                $funcName = 'getSitemapDataFor' . ucfirst($type);

                if (method_exists($movieService, $funcName))
                {
                    $data = $movieService->{$funcName}();

                    $view = \Response::view('anhi.webfilm::' . $data['view'], [
                        'list' => $data['data'],
                        'mobile' => $mobile]
                    )->content();

                    return $view;
                }

                return '';
            }
        );

        return \Response::make($view)->header('Content-Type', 'text/xml');
    }

    public function request()
    {

        $filmService = new FilmService;
        
        $link = Input::get("link", "");
        $type = Input::get('type', 0);

        return $filmService->request($link, $type);
    }

    function report ()
    {
    	$filmService = new FilmService;

        $result = $filmService->report();

        return redirect()
        			->back()
            		->withErrors(["message" => $result["returnMessage"]]);
    }

    function favourite ($movieId)
    {
    	$filmService = new FilmService;

    	$result = $filmService->favourite($movieId);

        return response()->json($result);
    }

    function watchLater ($movieId)
    {
    	$filmService = new FilmService;

    	$result = $filmService->watchLater($movieId);

        return response()->json($result);
    }

    function rate ($movieId)
    {
    	$filmService = new FilmService;

    	$result = $filmService->rate($movieId);

        return response()->json($result);
    }

    private function getLinkPlay ($movieId, $episode)
    {
        $result = ["status" => 1];

        $country_block=array("US", "GB", "FR", "RU" ,"CN" ,"TW", "TR", "CA" ,"AU");
        $country_code = isset($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : '';
        
        if (!in_array($country_code, $country_block))
        {

            if ($movieId > 0 && $episode > 0 )
            {
                $playInfo="";
                $watch_info="";

                $movieService = new MovieService;

                $playInfo = $movieService->getPlayFilmInfo($movieId, $episode);

                if ($playInfo)
                {
                    $watchInfo = $movieService->findOrAddWatchInfo($movieId, $episode);

                    $movieService->updateViewCount($movieId);

                    $this->updateEpisodeWatched($movieId, $episode);

                }
                $result['status']=0;
                $result['playinfo']= $playInfo;
                $result['watch_info']=$watch_info;
            }
        }

        return base64_encode(json_encode($result));
    }

    function updateEpisodeWatched ($movieId, $episode)
    {
        $user = Auth::getUser();

        if(!$user || !$episode)
            return;

        try {

            MovieEpisodeWatched::insert([
                'user_id' => $user->id,
                'movie_id' => $movieId,
                'episode' => $episode,
                'watch_time' => 0
            ]);
        }
        catch (\Exception $ex)
        {
            info(print_r($ex, 1));
        }
    }

    function getLink ($movieId, $episode)
    {
    
        $movieId=intval($movieId);
        $episode = intval($episode);

        $result_base64 = Cache::remember(

            config('cachekeys.linkplay') . $movieId . '_' . $episode,

            config("cache.link_play_expire"),

            function () use ($movieId, $episode) { return $this->getLinkPlay($movieId, $episode); }
        );

        return $result_base64;

    }

    public function search ()
    {

        $keyWord = Request::input("key", '');

        $getInfo = intval(Request::input("getInfo", 0));

        $page = Request::input('page', 0);

        if (empty($getInfo))
            $limit = 50;
        else
            $limit = 10;

        $movieService = new MovieService;

        $result = $movieService->searchMovies($keyWord,$getInfo, $page, $limit);

        return response()->json($result);
    }

    function sendContact ()
    {
        $data = [
            'email' => Input::get('email'),
            'name' => Input::get('name'),
            'content' => Input::get('content'),
            'created_at' => (new \DateTime)->format('Y-m-d h:i:s'),
            'status' => 0
        ];

        $contactService = new ContactService;

        try {

            $contactService->save($data);

        } catch (\Exception $ex) {

            info('Failed to save contact');
            info($ex->getMessage());

            return redirect(Helper::mobilePrefix() . "contact")->withErrors($contactService->getErrors())->with('message', 'An error has happened while sending. Please try again.');

        }
        
        return redirect(Helper::mobilePrefix() . 'contact')->with('message', 'Message Sent');
    }

    function sendResetPasswordMail ()
    {
        try {
            $rules = [
                'email' => 'required|email|between:6,255'
            ];

            $validation = Validator::make(post(), $rules);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            }

            if (!$user = UserModel::findByEmail(post('email'))) {
                return redirect()->back()->withErrors(['email' => 'Email does not exist']);
            }

            $code = $user->getResetPasswordCode();
            $link = url('/password/reset/' . $code);
            
            $data = [
                'name' => $user->name,
                'link' => $link,
            ];

            Mail::send('rainlab.user::mail.restore', $data, function($message) use ($user) {
                $message->to($user->email, $user->full_name);
            });

            return redirect()->back()->with('status', 'We have e-mailed your password reset link!');

        } catch (\Exception $ex) {
            
            return redirect()->withErrors(['status' => 'Failed to sent reset password link. Please try again.']);

        }
        
    }

    function resetPassword ()
    {
        $rules = [
            'code'     => 'required',
            'email' => 'email',
            'password' => 'required|between:4,255|confirmed'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

        /*
         * Break up the code parts
         */
        $email = post('email');

        $code = post('code');

        if (!strlen(trim($email)) || !($user = UserModel::where('email', $email)->first())) {
            return redirect()->back()
                            ->withErrors([
                                'email' => trans('rainlab.user::lang.account.invalid_user')
                            ]);
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            return redirect()->back()->withErrors([
                        'code' => trans('rainlab.user::lang.account.invalid_activation_code')
                    ]);
        }

        Auth::login($user);

        return redirect(Helper::mobilePrefix());
    }
}