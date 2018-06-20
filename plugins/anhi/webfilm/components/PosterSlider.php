<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\WebFilm\Services\FilmService;

use Helper, Cache;

class PosterSlider extends \Cms\Classes\ComponentBase
{
	public function defineProperties()
	{
	    return [
	        'type' => [
	             'title'             => 'Movie Type',
	             'description'       => 'Movie, Series, or Trailer',
	             'type'              => 'string',
	        ]
	    ];
	}

    public function componentDetails()
    {
        return [
            'name' => 'Poster Slider',
            'description' => 'Displays a collection of movies as a slider'
        ];
    }

    function getType ()
    {
    	$type = $this->property('type');

    	switch ($type)
    	{
    		case 'movies':
    			$type = 1;
    			break;
    		
    		case 'series':
    			$type = 2;
    			break;

    		case 'trailer':
    			$type = 3;
    			break;
    	}

    	return $type;
    }

    function getMovies ()
    {
        $type = $this->getType();

        $filmService = new FilmService;

        $movies = $filmService->getPosterMoviesByType($type);

        return $movies;
    }

    function buildViewCacheKey ()
    {
        $mobile = Helper::isMobile() ? '_mobile' : '';
        
        $type = $this->property('type');

        $cacheKey = config('cachekeys.views.posterslider') . "_{$mobile}_{$type}";

        if (Helper::isVip())
            $cacheKey .= '_vip_';

        return $cacheKey . Helper::mobilePrefix();
    }

    function onRender ()
    {
        $view = Cache::remember($this->buildViewCacheKey(), config('cache.view_expire'),
            function () {

                $type = $this->property('type');

                $view = $this->renderPartial('@default.htm', [
                    'movieList' => array_chunk($this->getMovies(), 10),
                    'assets' => '/plugins/anhi/webfilm/assets',
                    'type' => $type,
                ]);

                return $view;
            });

        return $view;
       
    }
}