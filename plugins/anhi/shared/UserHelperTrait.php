<?php

namespace Anhi\Shared;

use Cache;
use Exception;
use Session;
use Auth;
use DB;
use Anhi\Movie\Models\Notification;

use Anhi\Shared\Services\MovieService;

trait UserHelperTrait
{

	function sendVIPExpireNotification ()
    {
        $user = Auth::getUser();

        if ($user)
        {
            if ($user->send_notification == 0 && Helper::getDays($user->expire) < 10)
            {

            	$insert = Notification::insert([
            		'title' => 'VIP days',
            		'content' => 'Your have under 10 days left . Upgrade your VIP days now to enjoy more and more greate movies.',
            		'redirect_url' => '/payment-list',
            		'to' => $user->id
            	]);

            	if ($insert)
            	{
	            	$user->send_notification = 1;

	                $user->save();
            	}
            }
        }
    }

	public function isFavourite ($movie_id)
	{
		$user = Auth::getUser();

		if (empty($user))
		{
			return false;
		}
		else
		{

			$userId = $user->id;

			$movieService = new MovieService;

			$favourites = $movieService->getUserFavouriteMovieIds($userId);

			if (empty($favourites) || !in_array($movie_id, $favourites))
				return false;

			return true;
		}

	}

	public function checkEpisodeWatch($movie_id, $episode_id)
    {

        $user = Auth::getUser();

        if (empty($user))
        {
            return false;
        }
        else
        {
            $userId = $user->id;

            $result = DB::table('anhi_movie_movie_episode_watch')
                        ->where("user_id" , "=", $userId)
                        ->where("movie_id" , "=", $movie_id)
                        ->where('episode', $episode_id)
                        ->count();

            return $result > 0;
        }

    }

    public function isVip()
    {
        $user = Auth::getUser();

        if ($user)
        {
	        $expire = new \DateTime($user->expire);

	        return $expire > (new \DateTime);
        }

        return false;
    }

    public function userDays()
    {
        $user = Auth::getUser();

        return $this->getDays($user->expire);
        
    }

    public function getDays ($expire)
    {

    	if ( empty($expire) || (new \DateTime($expire))  < new \DateTime )
        	return 0;

        $now = time();

        $days = ceil((strtotime($expire) - $now) /60/60/24 );

		if ($days < 1)
			return 0;

		return $days;
    }

	public function isWatchLater ($movie_id)
	{
		$user = Auth::getUser();

		if(empty($user))
		{
			return false;
		}
		else
		{
			$userId = $user->id;

			$movieService = new MovieService;
			
			$list = $movieService->getListWatchLaterMovieIds($userId);

			if(in_array($movie_id, $list))
				return true;

			return false;
		}

	}



	// public  function checkEpisodeWatch($movie_id,$episode_id){
	// 	if(!isset(Auth()->user()->id)) {
	// 		return false;
	// 	}else{
	// 		$key='episode_watch_'.$movie_id;

	// 		if(!isset(PersonalHelper::$personalData[$key])){
	// 			$userId = Auth()->user()->id ;
	// 	        $movie = new \App\Movie();
	// 			PersonalHelper::$personalData[$key]=$movie->getEpisodeWatch($userId,$movie_id);
	// 		}

	// 		if(in_array($episode_id, PersonalHelper::$personalData[$key])){
	// 			return true;
	// 		}else{
	// 			return false;
	// 		}


	// 	}

	// }


	public function registerNewVIPSession ()
	{
		if(!$this->isVip())
		{
			return false;
		}

		$userId = Auth::getUser()->id ;

		Cache::forever(config("cacheKey.personal.vip_session")."_".$userId, session()->getId());
	}

	// public  function isKickVipSession(){


	// 	if(!Helper::isVip()){
	// 		return false;
	// 	}



	// 	$userId = Auth()->user()->id ;
	// 	$current=Cache::get(config("cacheKey.personal.vip_session")."_".$userId, '');


	// 	if($current==''){
	// 		return false;
	// 	}else if($current!=session()->getId()){
	// 		// Kick
	// 		return true;
	// 	}


	// 	//Others / Not K
	// 	return false;
	// }


	// public  function getViewCacheKey($view_name){


	// 	$view_cache_info = array();

	// 	$display=Helper::isMobile()?"mobile":"desktop";

	// 	$viewKey=$view_name."_".\Lang::locale()."_".$display;
	// 	$view_tag = "page_html";




	// 	if(isset($_SERVER['SERVER_NAME'])){

	// 		$viewKey.=$_SERVER['SERVER_NAME'];

	// 	}


	// 	if(isset($_SERVER['QUERY_STRING'])){

	// 		$viewKey.=md5($_SERVER['QUERY_STRING']);

	// 	}

	// 	if(isset(Auth()->user()->id)) {
	// 		$viewKey = $viewKey."_".\Session::getId();
	// 		$view_tag.="_".Auth()->user()->id;
	// 	}



	// 	$country_code = isset($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : '';

	// 	if($country_code){
	// 		$viewKey = $viewKey."_".$country_code;	
	// 	}



	// 	$view_cache_info['viewKey'] = $viewKey;
	// 	$view_cache_info['viewTag'] = $view_tag;


	// 	Session::push('html_cache_keys', $viewKey);


	// 	return $view_cache_info;



 //    }

    public function removeHtmlTagById ()
    {
    	if(empty(Auth::getUser()))
    	{
    		return ;
    	}

    	$list_html_keys = Session::pull('html_cache_keys', []);

    	foreach ($list_html_keys as $cacheKey)
    	{
    		$this->forgetCache(config("cacheKey.view.html")."_".$cacheKey,['movie']);
    	}
    }

}