<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper, Cache, Auth;

use Anhi\Shared\Services\MovieService;

class UserFavourite extends \Cms\Classes\ComponentBase
{

    protected $related = false;

    public function componentDetails()
    {
        return [
            'name' => 'User favourite movies',
            'description' => 'Displays a collection of movies'
        ];
    }

    function buildList ($movie)
    {

        $listFavourite = [];

        $favourite = 0;

        $user = Auth::getUser();

        $movieService = new MovieService;

        if ($user)
        {
            $listFavourite = $movieService->getFavouriteMovies($user->id);
            $favourite = count($listFavourite);
        }

        return [
            'listFavourite' => $listFavourite,
            'favourite' => $favourite
        ];
    }

    function onRender ()
    {
        $view = Cache::remember($this->buildViewCacheKey(), config('cache.view_expire'),
            function () {

                $result = $this->buildList();

                $view = $this->renderPartial('@default.htm', $result);

                return $view;
            });

        return $view;
    }


    function buildViewCacheKey ()
    {
        $mobile = Helper::isMobile() ? '_mobile' : '';

        $user = Auth::getUser();

        $cacheKey = config('cachekeys.view.favourite');

        if ($user)
            $cacheKey .= '_' . $user->id;

        $cacheKey .= $mobile;

        return $cacheKey . Helper::mobilePrefix();
    }

}