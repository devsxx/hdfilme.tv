<?php namespace Anhi\Webfilm;

use System\Classes\PluginBase;

use Helper, Session;

use RainLab\User\Models\User;

use Auth, App;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
	        'Anhi\WebFilm\Components\BannerSlider' => 'bannerSlider',
	        'Anhi\WebFilm\Components\PosterSlider' => 'posterSlider',
            'Anhi\WebFilm\Components\MovieList' => 'movieList',
            'Anhi\WebFilm\Components\MovieInfo' => 'movieInfo',
            'Anhi\WebFilm\Components\MovieInfoComment' => 'movieInfoComment',
            'Anhi\WebFilm\Components\MovieActionBar' => 'movieActionBar',
            'Anhi\WebFilm\Components\Sidebar' => 'sidebar',
            'Anhi\WebFilm\Components\UserMovie' => 'userMovie',
            'Anhi\WebFilm\Components\Payment' => 'payment',
            'Anhi\WebFilm\Components\AntiBlock' => 'antiblock',
            'Anhi\WebFilm\Components\News' => 'news',
            'Anhi\WebFilm\Components\NewsDetails' => 'newsDetails',
            'Anhi\WebFilm\Components\PaymentHistory' => 'paymentHistory',

	    ];
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [

            ],
            'functions' => [
                
                'makeSeo' => [Helper::class, 'makeSeo'],

                'isMobile' => [Helper::class, 'isMobile'],

                'formatDate' => function ($strDate, $format) {
                    return (new \DateTime($strDate))->format($format);
                },

                'checkEpisodeWatch' => [Helper::class, 'checkEpisodeWatch'],

                'count' => function ($arr) { return  count($arr); },

                'log' => function ($info) { info(print_r($info, true)); },

                'isVip' => [Helper::class, 'isVip'],

                'isFavourite' => [Helper::class, 'isFavourite'],

                'userDays' => [Helper::class, 'userDays'],

                'is_array' =>  function ($value) {
                    return is_array($value);
                },

                'urlencode' =>  function ($value) {
                    return urlencode($value);
                },

                'now' => function () {
                    return (new \DateTime)->format('Y-m-d h:i:s');
                },

                'mobilePrefix' => [Helper::class, 'mobilePrefix'],

                'isWatchLater' => [Helper::class, 'isWatchLater'],

                'str_content' => function ($content, $find) {
                    return strpos($content, $find) === false;
                },

                'toArray' => function ($object) {
                    $result = [];

                    foreach ($object as $key => $value)
                    {
                        $result[$key] = $value;
                    }

                    return $result;
                },

                'session' => function ($key) {
                    return Session::get($key);
                }
                ,
                'audioQuality' => function ($value) {
                    // return Helper::audioQuality()[$value]['name'];
                    return '';
                },
                'movieQuality' => function ($value) {

                    $qualities = Helper::movieQuality();

                    return empty($qualities[$value]) ? '' : $qualities[$value] ;
                },

            ]
        ];
    }

    function boot ()
    {
        User::extend(function ($model) {
            
            $model->belongsToMany['movies'] = [

                'Anhi\Movie\Models\Movie',

                'table' => 'movie_favorite',

                'key' => 'user_id',

                'otherKey' => 'movie_id'
            ];

            $model->attachOne['avatar'] = [

                'Anhi\Movie\Models\File',

                'public' => true
            ];
        });

        $this->sendVIPExpireNotification();
    }

    function sendVIPExpireNotification ()
    {
        $user = Auth::getUser();

        if ($user)
        {
            info(Helper::getDays($user->expire));

            if ($user->send_notification == 0 && Helper::getDays($user->expire) < 10)
            {
                \DB::statement("
                    insert into anhi_movie_notifications_users (user_id, notification_id, web_noti_status) values({$user->id}, 1, 0) on duplicate update web_noti_status = 0"
                );

                $user->send_notification = 1;

                $user->save();
            }
        }
    }
}
