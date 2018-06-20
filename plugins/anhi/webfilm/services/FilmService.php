<?php

namespace Anhi\WebFilm\Services;

use Auth, Input, Helper, DB;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\MovieRequest;

use Anhi\Movie\Models\MovieRating;

use Anhi\Shared\Services\MovieService;

use CacheService;

use Cache, Lang;

class FilmService
{
    function __construct ()
    {
    }

    function getBannerMovies ($page = 0, $limit = 8)
    {
        $ids = CacheService::getListIds(

            config('cachekeys.bannerMovieIds'),

            function () use ($page, $limit) {

                $movieIds = Movie::where("slide", ">", 0)
                                ->where("hide", "0")
                                ->orderBy("slide", "desc")
                                ->skip($page)
                                ->take($limit)
                                ->get()
                                ->lists('id');

                return $movieIds;
        });

        $movies = CacheService::getMovies($ids);
        
        return $movies;
    }

    function getPosterMoviesByType ($type)
    {
        $movieIds = CacheService::getListIds(

            config('cachekeys.posterSliderId') . $type,

            function () use ($type) {
                
                $page = 0;
                $limit = 20;

                $movieIds = Movie::where("type", $type)
                                ->where("hide", "0")
                                ->orderBy("top", "desc")
                                ->skip($page)
                                ->take($limit)
                                ->select('id')
                                ->get()
                                ->lists('id');

                return $movieIds;
        });

        $movies = CacheService::getMovies($movieIds);

        return $movies;
    }

	function report ()
	{
		$result = [
            "returnCode" => 0,
            "returnMessage" => \Lang::get("anhi.webfilm::messages.mustLogin")
        ];

		$link = $_SERVER["HTTP_REFERER"];

        $user = Auth::getUser();

        if(!empty($link) && $user)
        {
            $this->insertReport($user, $link);

            $result = [
                "returnCode" => 1,
                "returnMessage" => \Lang::get("anhi.webfilm::messages.reportFilmSuccess")
            ];
        }

        return $result;
	}

	private function insertReport ($user, $link)
	{
		$data = [
            "user_id" => $user->id,
            "user_email" => $user->email,
            "movie_id" => intval(Input::get("movie_id", '')),
            "movie_name" => Input::get("movie_name", ''),
            "link" => $link,
            "movie_error" => implode(", ", Input::get("movie_error", [])),
            "audio_error" => implode(", ", Input::get("audio_error", [])),
            "episode" => intval(Input::get("episode", 1)),
            "description" => Input::get("description", ''),
            'date_add' => (new \DateTime)->format('Y-m-d h:i:s')
        ];

        return DB::table("anhi_movie_movie_error_reports")->insert($data);
	}

	public function favourite ($id, $table = 'anhi_movie_movie_favourite')
	{
        $result = Input::all();
        $result["result"] = 0;
        $user = Auth::getUser();
        
        $movie = CacheService::getMovie($id);

        if ($movie && $movie['hide'] == 0 && $user)
        {
            
        	$result = array_merge($result, $this->insertFavourite($id, $user->id, $table));

            $this->clearCacheFavourite($table, $user);

            $result["result"] = 1;
        }

        return $result;
    }

    function clearCacheFavourite ($table, $user)
    {
        if ($table === 'anhi_movie_movie_favourite')
        {
            Cache::forget(config('cachekeys.favouriteIds') . $user->id);

            $mobile = Helper::isMobile() ? '_mobile' : '';

            //because sidebar displays user favourite movies
            Cache::forget(config('cachekeys.views.favourite') . "_{$user->id}" . $mobile);
        }
        else
            Cache::forget(config('cachekeys.watchLaterIds') . $user->id);
    }

    private function insertFavourite ($movieId, $userId, $table='anhi_movie_movie_favourite')
    {
        $movieService = new MovieService;

    	$result = [];

        $listIds = [];

        if ($table === 'anhi_movie_movie_favourite')
        	$listIds = $movieService->getUserFavouriteMovieIds($userId);
        else
            $listIds = $movieService->getListWatchLaterMovieIds($userId);

    	if (in_array($movieId, $listIds))
    	{
    		 DB::table($table)
            	->where("movie_id", $movieId)
                ->where("user_id" , "=", $userId)
            	->delete();

            $result["active"] = false;
    	}
    	else
    	{
    		 DB::table($table)
            	->insert(["movie_id" => $movieId, "user_id" => $userId]);

            $result["active"] = true;
    	}

        return $result;
    }
    
    function watchLater ($id)
    {
        $result = $this->favourite($id, 'anhi_movie_movie_watch_later');


        return $result;
    }

    function rate ($id)
    {
        $user = Auth::getUser();
        $id = intval($id);

        $result["msg"] = \Lang::get("anhi.webfilm::messages.rateDuplicate");

        $rated = MovieRating::where('user_id', $user->id)
                            ->where('movie_id', $id)
                            ->count();

        if ($rated)
            return $result;

        try
        {
            $movie = CacheService::getMovie($id);

            $movie['rate_total'] = (int)$movie['rate_total'] + (int)Input::get("score");
            $movie['rate_count'] = (int)$movie['rate_count'] + 1;
            $rate = round($movie['rate_total'] / $movie['rate_count'], 1);

            Movie::where("id", $id)
            	->update([
                    "rate" => $rate,
                    "rate_count" => $movie['rate_count'],
                    "rate_total" => $movie['rate_total']
                ]);

            MovieRating::insert([
                    'user_id' => $user->id,
                    'movie_id' => $id
                ]);

            $result["msg"] = \Lang::get("anhi.webfilm::messages.rateSuccess");

            CacheService::cacheMovie($movie);

        } catch (QueryException $e) { 
            info($e->getMessage());
        }

        return $result;
    }

    public function request($link, $type)
    {
        $result = [
            "returnCode" => 0,
            "returnMessage" => Lang::get("anhi.webfilm::messages.noVip")
        ];

        if (Helper::isVip())
        {
            $result = [
                "returnCode" => -1,
                "returnMessage" => Lang::get("anhi.webfilm::messages.formError")
            ];

            if (!empty($link))
            {
                $linkIndex = md5($link);

                $movie = MovieRequest::where('link_index', $linkIndex)
                                        ->where('status', 1)
                                        ->count();

                if (!empty($film))
                {
                    $result = [
                        "returnCode" => 2,
                        "returnMessage" => Lang::get("anhi.webfilm::messages.requestFilmSuccess"),
                        "link" => $film->process_link
                    ];
                }
                else
                {

                    $user = Auth::getUser();
                    $data = [
                        "user_id" => $user->id,
                        "user_email" => $user->email,
                        "link" => $link,
                        "link_index" => $linkIndex,
                        "date_add" => (new \DateTime)->format('Y-m-d h:i:s'),
                        "type" => empty($type) ? 1 : $type,
                    ];

                    MovieRequest::insert($data);

                    $result = [
                        "returnCode" => 1,
                        "returnMessage" => Lang::get("anhi.webfilm::messages.requestFilmSubmit")
                    ];
                }
            }
        }
        
        return redirect()->back()
            ->withErrors(["message" => $result["returnMessage"]]);
    }
}