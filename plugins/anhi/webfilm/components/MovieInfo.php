<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\Category;

use Anhi\Movie\Models\Country;

use Helper, Request, Input, DB, Cache;

use Anhi\Shared\Services\MovieService;

class MovieInfo extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Movie List',
            'description' => 'Displays a collection of movies'
        ];
    }

    public function defineProperties()
	{
	    return [
	        'filmName' => [
	             'title'             => 'Movie Type',
	             'description'       => 'Movie, Series, or Trailer',
	             'type'              => 'string',
	        ]
	    ];
	}

	function onRender ()
	{
		$assets = '/plugins/anhi/webfilm/assets';

		$movieService = new MovieService;

		$this->page['slug'] = $this->property('slug');

		$data = $movieService->getMovieInfo($this->page['slug']);

		if (empty($data))
			return \Redirect::to('/404');

        $this->page['assets'] = $assets;

        $this->page['movie'] =  $data['movie'];

        $this->page['type'] =  $data['viewType'];

        $this->page['download'] = $data['download'];

        $this->page['currentEpisode'] = $data['currentEpisode'];
	}

}