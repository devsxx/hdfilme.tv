<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper, Cache, Auth;

use Anhi\Shared\Services\MovieService;

class Sidebar extends \Cms\Classes\ComponentBase
{

    protected $related = false, $movieService = null;

    public function componentDetails()
    {
        return [
            'name' => 'Movie Sidebar',
            'description' => 'Displays a collection of movies'
        ];
    }

    function buildList ($movie)
    {
        $listMore = [];

        $isUsingTabs = 0;

        if ($movie)
        {
            $listMore["movieRelated"] = $this->movieService->getRelatedMovies($movie);
        }
        else
        {
            $isUsingTabs = 1;

            $listMore["mostMovieView"] = [

                "month" => $this->movieService->getMostViewList(1,'view_in_months') ,

                "week" => $this->movieService->getMostViewList(1,'view_in_week') ,
            ];

            //get list most viewed tv show
            $listMore["mostTvShowView"] = [

                "month" => $this->movieService->getMostViewList(2,'view_in_months') ,

                "week" => $this->movieService->getMostViewList(2,'view_in_week') ,
            ];
        }

        return [
            'isUsingTabs' => $isUsingTabs,
            'listMore' => $listMore,
        ];
    }

	function onRender ()
	{
        $this->movieService = new MovieService;

        $data = $this->movieService->getMovieInfo($this->property('slug'));

        if (isset($data['movie']))
            $movie = $data['movie'];
        else
            $movie = null;

        $view = Cache::remember($this->buildViewCacheKey($movie), config('cache.view_expire'),
            function () use ($movie) {

                $result = $this->buildList($movie);

                $view = $this->renderPartial('@default.htm', array_merge([
                    'assets' => '/plugins/anhi/webfilm/assets',
                ], $result));

                return $view;
            });

        return $view;
	}


    function buildViewCacheKey ($movie)
    {
        $mobile = Helper::isMobile() ? '_mobile' : '';

        $cacheKey = config('cachekeys.views.sidebar');

        if ($movie)
            $cacheKey .= '_' . $movie['id'];

        if (Helper::isVip())
            $cacheKey .= '_vip_';

        $cacheKey .= $mobile;

        return $cacheKey . Helper::mobilePrefix();
    }

}