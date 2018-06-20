<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\WebFilm\Services\FilmService;

use Helper, Cache;

use Illuminate\Support\Facades\Redis;

class BannerSlider extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Banner Slider',
            'description' => 'Displays a collection of movies as a slider'
        ];
    }

    // This array becomes available on the page as {{ component.posts }}
    function films()
    {
        $filmService = new FilmService;

        $movies = $filmService->getBannerMovies();

        return $movies;
    }


    function onRender ()
    {

        $mobile = Helper::isMobile() ? '_mobile_' : '';

        $mobile .= Helper::mobilePrefix();

        $view = Cache::remember(config('cachekeys.views.bannerslider') . $mobile, config('cache.view_expire'),
            function () {

            $films = $this->films();

            $view = $this->renderPartial('@default.htm', [
                'films' => $films,
                'assets' => '/plugins/anhi/webfilm/assets'
            ]);

            return $view;
        });

        return $view;
    }
}