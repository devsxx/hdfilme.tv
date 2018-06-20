<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\Category;

use Anhi\Movie\Models\Country;

use Helper, Request, Input, DB;

use Anhi\Shared\Services\MovieService;

class MovieInfoComment extends \Cms\Classes\ComponentBase
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

        $this->page['assets'] = $assets;

        $movieService = new MovieService;
        
        $data = $movieService->getMovieInfo($this->property('slug'));

        $this->page['movie'] = $data['movie'];

        $this->page['episodeInfo'] = $data['episodeInfo'];

        $this->page['currentEpisode'] = $data['currentEpisode'];
	}
}