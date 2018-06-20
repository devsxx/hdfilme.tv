<?php

namespace Anhi\Shared\Services;

use Anhi\Movie\Models\Movie;

use Cache;

class CacheService
{

	private $redis;

	private $movieAttachFields = [
		'actors', 'producers', 'directors',
		'categories', 'tags', 'poster', 'banner', 'links',
		'country'
	];

	function getListIds ($cacheKey, $cb)
	{
		$ids = Cache::get($cacheKey);

		if (!is_array($ids))
		{
			$ids = $cb();

			Cache::put($cacheKey, $ids, config('cache.expire'));
		}

		return $ids;
	}

	function getPaging ($cacheKey, $cb)
	{
		$total = Cache::get($cacheKey . '_total');

		if (!is_numeric($total) || $total < 1)
		{
			$total = $cb();

			Cache::put($cacheKey . '_total', $total, config('cache.expire'));
		}

		return $total;
	}

	function cacheMovie ($movie)
	{
		if (empty($movie))
			return null;

		$cacheKey = config('cachekeys.movie') . '_' . $movie['id'];

		Cache::put($cacheKey, $movie, config('cache.expire'));

		return $movie;
	}

	function getMovies ($ids)
	{
		$movies = [];

		$notCached = [];
		
		$cacheKey = config('cachekeys.movie');

		foreach ($ids as $id)
		{

			$movie = Cache::get($cacheKey . '_' . $id);

			if (empty($movie))
				$notCached[] = $id;
		}

		if (count($notCached))
		{
			$moviesFromDB =  $this->loadMovies($notCached);

			foreach ($moviesFromDB as $movie)
			{
				Cache::put($cacheKey . '_' . $movie['id'], $movie, config('cache.expire'));
			}
		}

		//get movie after cache all to ensure order as input array
		foreach ($ids as $id)
		{
			$movies[] = Cache::get($cacheKey . '_' . $id);
		}

		return $movies;
	}

	function getMovie ($id)
	{

		$cacheKey = config('cachekeys.movie');

		$movie = Cache::get($cacheKey . '_' . $id);

		if (empty($movie))
		{
			$movies = $this->loadMovies([$id]);

			$movie = $this->cacheMovie(empty($movies[0]) ? null : $movies[0]);
		}

		return $movie;
	}

	protected function loadMovies ($ids)
	{
		$movies = Movie::with([
						'actors', 'producers', 'directors',
						'categories', 'tags', 'poster', 'banner', 'links' => function ($query) {
							$query->orderBy('episode', 'asc');
						},
						'country'
					])->whereIn('id', $ids)->get()->toArray();

		return $movies;
	}
}