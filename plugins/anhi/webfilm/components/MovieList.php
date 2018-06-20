<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\Actor;

use Anhi\Movie\Models\Director;

use Anhi\Movie\Models\Category;

use Anhi\Movie\Models\Country;

use Helper, Request, Input, DB;

use Anhi\Shared\Services\MovieService;

use CacheService, Cache;

class MovieList extends \Cms\Classes\ComponentBase
{
	protected $cacheKey = '', $orderBy, $filters, $titleAndCode;

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
	        'type' => [
	             'title'             => 'Movie Type',
	             'description'       => 'Movie, Series, or Trailer',
	             'type'              => 'string',
	        ]
	    ];
	}

	function buildViewCacheKey ()
	{
		$key = $this->cacheKey . (Helper::isMobile() ? '_mobile' : '');

		$key .= '_page' . Input::get("page", 1);

		if ($this->property('type') === 'movie-search')
			$key .= '_search_' . Request::input("key", '');

		if (Helper::isVip())
            $key .= '_vip_';

		return $key . '_view_' . Helper::mobilePrefix();
	}

	function onRender ()
	{

		$type = $this->property('type');

		$this->titleAndCode = $this->getTitleAndCode($type);

		$this->buildCacheKey();

		return Cache::remember($this->buildViewCacheKey(), config('cache.view_expire'), function () {

			$type = $this->property('type');

			$page = Input::get("page", 1);
			
	        $limit = Input::get("limit", 50);

	        $skip = (($page -1 ) *  $limit);

			if ($type === 'movie-search')
			{
				$result = $this->doSearch($type, $skip, $limit, $page);
			}
			else
			{
				$result = $this->getMovieList($type, $skip, $limit, $page);
			}

			$view = $this->renderPartial('@default.htm', array_merge([
                'assets' => '/plugins/anhi/webfilm/assets',
            ], $result));


            return $view;

		});
	}

	function getCountries ()
	{
		return Cache::remember(config('cachekeys.countries'), 30, function () {
				return Country::all()->toArray();
			});
	}

	function getCats ()
	{
		return Cache::remember(config('cachekeys.cats'), 30, function () {
				return Category::all()->toArray();
			});
	}

	private function getMovieList ($type, $skip, $limit, $page)
	{
		$result = [];

		if ($type === 'cat')
			$cat = $this->property('id');
		else
			$cat= Input::get('category', null);

		$result['filters'] = [

			'category' => $this->getCats(),

			'country' => $this->getCountries(),

			'sort' => $this->getSorList(),

		];

		$result['filterValues'] = [
			"sort" => Input::get('sort', 'top'),
			"sort_type" => Input::get('sort_type', 'desc'),
			"category" => $cat,
			"country" => Input::get('country', null)

		];

		$result['title'] = $this->titleAndCode['title'];
		$type = $this->titleAndCode['code'];

		$movie = new Movie;

        $query = $movie->setTable('anhi_movie_movies as m')
        				->leftJoin('anhi_movie_movie_category as mc', 'm.id', '=', 'mc.movie_id')
        				->leftJoin('anhi_movie_movie_actor as ma', 'm.id', '=', 'ma.movie_id')
        				->leftJoin('anhi_movie_movie_director as md', 'm.id', '=', 'md.movie_id');
        
        if (!empty($type))
        	$query->where('m.type', $type);

        $query = $this->appendWhere($query, $this->filters);

        $total = 0;

		$totalQuery = $query->selectRaw('count(distinct m.id) as total')
						->get()
						->first();

        $movieIds = CacheService::getListIds(

		        		$this->cacheKey  . "_page_{$page}",

		        		function () use ($query, $skip, $limit) {

		        			if (count($this->orderBy) == 2 && !empty($this->orderBy[0]) && !empty($this->orderBy[0]))
		        			{
		        				$query = $query->orderBy(...$this->orderBy);
		        			}

			        		return $query->skip($skip)
				        				->take($limit)
				        				->select('m.id', 'top')
				        				->distinct()
				        				->get()->lists('id');
		        		}
					);

        $movies = CacheService::getMovies($movieIds);

		if ($totalQuery)
			$total = $totalQuery->total;

        $result['paging'] = Helper::paging($total, $limit, $page);

        $result['movieList'] = $movies;

        return $result;
	}

	private function buildCacheKey ()
	{
		$filterStr = '';

		$this->filters = $this->getFilter();

		$this->orderBy = $this->getOrderBy();

		$type = $this->titleAndCode['code'];
		
		if (!empty($this->filters))
		{
			foreach ($this->filters as $filter)
			{
				$filterStr = implode('_', array_values($filter));
			}
		}

		$this->cacheKey = config('cachekeys.list') . implode('_', $this->orderBy) . $filterStr . "_{$type}";


		switch ($this->property('type')) {

        	case 'movie-director':
        		$this->cacheKey .= '_director_' . $this->property('directorId');
        		break;
        	
        	case 'movie-actor':
        		$this->cacheKey .= '_actor_' . $this->property('actorId');
        		break;
        }
	}

	private function appendWhere ($query, $filters)
	{
		foreach ($filters as $where)
        {
        	if (count($where) !== 2)
        		continue;
        	
        	$query->where(...$where);
        }

        switch ($this->property('type')) {

        	case 'movie-director':
        		$query->where('md.director_id', $this->property('directorId'));
        		break;
        	
        	case 'movie-actor':
        		$query->where('ma.actor_id', $this->property('actorId'));
        		break;
        }

        return $query;
	}

	private function doSearch ($type, $skip, $limit, $page)
	{
		$keyword = Request::input("key", '');

		$movieService = new MovieService;

    	$result = $movieService->searchMovies($keyword, 0, $skip, $limit);

    	$result['movieList'] = $result['film'];

    	$total = $movieService->buildSearchMovieQuery($keyword)
    						->selectRaw('count(*) as total')
    						->get()
    						->first()->total;

    	$result['paging'] = Helper::paging($total, $limit, $page);

    	$result['title'] = 'Suchen';

    	$result['total'] = $total;

    	$result['keyword'] = $keyword;

    	return $result;
	}

	private function getTitleAndCode ($type)
	{
		switch ($type)
		{

			case 'movie-movies':
				return [
					'title' => 'filme',
					'code' => 1
				];

			case 'movie-series':
				return [
					'title' => 'series',
					'code' => 2
				];
			
			case 'movie-trailer':
				return [
					'title' => 'trailer',
					'code' => 3
				];

			case 'movie-director':

				$directorId = $this->property('directorId');
				$directorName = Cache::remember(config('cachekeys.director') . $directorId, 60, function () use ($directorId) {
								return Director::find($directorId)->director_name;
							});

				return [
					'title' => 'Regisseur: ' . $directorName,
					'code' => ''
				];

			case 'movie-actor':

				$actorId = $this->property('actorId');
				$actorName = Cache::remember(config('cachekeys.actor') . $actorId, 60, function () use ($actorId) {
								return Actor::find($actorId)->actor_name;
							});

				return [
					'title' => 'Schauspieler: ' . $actorName,
					'code' => ''
				];
		}
	}

	private function getOrderBy ()
	{
		$orderBy = [Input::get('sort'), Input::get('sort_type')];

		if (empty($orderBy[0]))
			$orderBy[0] = 'top';

		if (empty($orderBy[1]))
			$orderBy[1] = 'desc';

		return $orderBy;
	}

	private function getFilter ()
	{
		$filters = [];

		$type = $this->property('type');

		$catFilters = $this->getCatFilter($type);

		if (!empty($catFilters))
			$filters[] = $catFilters;

		if ($type === 'movie-actor')
		{
			$filters[] = $this->getActorFilter();
		}

		$country = Input::get('country', null);

		if (is_numeric($country))
			$filters[] = ['m.country_id', $country];

		return $filters;
	}

	private function getCatFilter ($type)
	{
		$result = null;

		if ($type === 'cat')
		{
			$cat = $this->property('id');
			$result = ['mc.category_id', $cat];
		}
		else
		{
			$cat= Input::get('category', null);

			if (is_numeric($cat))
				$result = ['mc.category_id', $cat];
		}

		return $result;
	}

	private function getActorFilter ()
	{
		$actorId = $this->property('actorId');

		return ['ma.actor_id', $actorId];
	}

	private function getSorList ()
	{
		return [
			[
				"id" => "top",
				"sort_name" => 'Updated'
			],
			[
				"id" => "year",
				"sort_name" => 'Year'
			],
			[
				"id" => "name",
				"sort_name" => 'Name'
			],
			[
				"id" => "imdb",
				"sort_name" => 'IMDB'
			],
			[
				"id" => "rate",
				"sort_name" => 'Rate'
			],
			[
				"id" => "view",
				"sort_name" => 'View'
			],
		];
	}
}