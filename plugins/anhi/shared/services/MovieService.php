<?php

namespace Anhi\Shared\Services;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\Category;
use Anhi\Movie\Models\Actor;
use Anhi\Movie\Models\Director;

use Anhi\Movie\Models\MovieReport;

use Anhi\Movie\Models\MovieRequest;

use Anhi\Movie\Models\MovieFavourite;

use Anhi\Movie\Models\DownloadFile;

use Anhi\Movie\Models\MovieWatchLater;

use Anhi\Movie\Models\MovieEpisodeWatched;

use DB, Input, Helper, Auth, Session, Cache;

use CacheService as CacheFilm;

class MovieService
{

    function getSiteMapDataForMovies ()
    {

        $data = Cache::remember(

                    config('cachekeys.sitemap.movies'),

                    config("cache.sitemap_expire"),

                    function() {
                        return Movie::select('id', 'friendly_url', 'updated_at')->get()->toArray();
                    }
                );

        return [
            'data' => $data,
            'view' => 'sitemap_movies'
        ];

    }

    function getSitemapDataForCats ()
    {
        $data = Cache::remember(

                    config('cachekeys.sitemap.cats'),

                    config("cache.sitemap_expire"),
                    
                    function() {
                        return Category::select('id', 'category_name')->get()->toArray();
                    }
                );

        return [
            'data' => $data,
            'view' => 'sitemap_cats'
        ];
    }

    function getSitemapDataForActors ()
    {
        $data = Cache::remember(

                    config('cachekeys.sitemap.actors'),

                    config("cache.sitemap_expire"),
                    
                    function() {
                        return Actor::select('id')->get()->toArray();
                    }
                );

        return [
            'data' => $data,
            'view' => 'sitemap_actors'
        ];
    }

    function getSitemapDataForDirectors ()
    {
        $data = Cache::remember(

                    config('cachekeys.sitemap.directors'),

                    config("cache.sitemap_expire"),
                    
                    function() {
                        return Director::select('id')->get()->toArray();
                    }
                );

        return [
            'data' => $data,
            'view' => 'sitemap_directors'
        ];
    }

	function getMovieInfo($slug = '')
	{
        $arr = explode("-", $slug);

        if (count($arr) < 2 || !isset($arr[count($arr) - 2]) || intval($arr[count($arr) - 2]) <= 0)
            return null;

        $movie_id = intval($arr[count($arr) - 2]);
        $view_type = $arr[count($arr) - 1];

        $info = CacheFilm::getMovie($movie_id);

        if (empty($info))
            return null;

        $data['episodeInfo'] = $info['links'];

        //setup play moview info
        $episode = 0;
        $download = [];

        if ($view_type == "stream")
        {
            //play film
            $episode = Input::get("episode", 1);

            $movieName = "HDfilme.TV-Kino.stream.HD-" . $info['name'];
            $movieName = str_replace(' ', '-', $movieName);

            $download = $this->getDownloadFilmInfo($info, $episode);
        }
        else if ($view_type == "trailer")
        {
            $episode = 1;

            if (!empty($info['demo_link']))
            {
                $parts = parse_url($info['demo_link']);

                parse_str($parts["query"], $query);

                $info['demo_link'] = "https://www.youtube.com/embed/{$query["v"]}";
            }
            else
            {
                return null;
            }
        }

        $data["movie"] = $info;
        $data['viewType'] = $view_type;
        // $data["avatar"] = $this->getAvatar();
        $data["currentEpisode"] = $episode;

        $data["download"] = $download;

        return $data;
    }

    public function getDownloadFilmInfo ($movie, $episode)
    {
        $movie_id=$movie['id'];
        $filminfo = null;

        foreach ($movie['links'] as $link)
        {
            if ($link['episode'] == $episode)
                $filminfo = $link;
        }

        if ($filminfo)
        {
            $IDLink =  explode("#", $filminfo['url']);

            $gid = $IDLink[1];

            $linkInfo = $this->getLinks($gid);
        } 

        if (empty($linkInfo) || !is_array($linkInfo))
            $linkInfo = [];

        return $linkInfo;

    }

    function isBlockedCountry () {

        $country_block = ["US", "GB", "FR", "RU" ,"CN" ,"TW", "TR", "CA" ,"AU"];

        $country_code = isset($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : '';
        
        return empty($country_block[$country_code]) ? false : true;
    }

    private function getLinks ($gid)
    {

        if ($this->isBlockedCountry())
            return [];

        $gid = str_replace(';', '', $gid);

        return Cache::remember(

            config('cachekeys.linkplay') . $gid,

            config("cache.link_play_expire"),

            function () use ($gid) {

                try {

                    $links = file_get_contents("http://cdn.hdfilme.tv/d0stream.php?drive_id={$gid}");

                    // info("http://gid.hdfilme.tv/api/resolution/{$gid}");

                    $links = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $links);

                    // info($links);

                    // info(json_decode(trim($links), true));

                    return json_decode(trim($links), true);

                } catch (\Exception $ex) {

                    info(print_r($ex, 1));
                }
            }
        );
        
    }

    function getTimeParameters()
    {
        $options = new MediaVaultOptions();
        $options->startTime = time();
        $options->endTime = $options->startTime + 60 * 60 * 3; //5 minutes from now
        return $options;
    }

    function getListWatchLaterMovieIds ($userId, $from=1, $limit=50)
    {

        $watchLaterIds = CacheFilm::getListIds(
            config('cachekeys.watchLaterIds') . $userId,
            function () use ($userId) {
                $watchLaterIds = MovieWatchLater::where('user_id', $userId)
                                                ->get()
                                                ->lists('movie_id');
                return $watchLaterIds;
            }
        );

        return $watchLaterIds;
    }

    function getFavouriteMovies ($userId, $from=1, $limit=50, $table='anhi_movie_movie_favourite')
    {
        if ($table === 'anhi_movie_movie_favourite')
            $movieIds = $this->getUserFavouriteMovieIds($userId, $from, $limit);
        else
            $movieIds = $this->getListWatchLaterMovieIds($userId, $from, $limit);

        $movies = CacheFilm::getMovies($movieIds);

        return $movies;
    }

    function getUserFavouriteMovieIds ($userId, $from=1, $limit=50)
    {
        if (empty($userId))
            return [];
        
        $favouriteIds = CacheFilm::getListIds(
            config('cachekeys.favouriteIds') . $userId,
            function () use ($userId) {
                $favouriteIds = MovieFavourite::where('user_id', $userId)
                                                ->get()
                                                ->lists('movie_id');

                return $favouriteIds;
            }
        );

        return $favouriteIds;
    }

    function getRelatedMovies ($movie)
    {

        $relatedIds = $this->getRelatedIds($movie);

        return CacheFilm::getMovies($relatedIds);
    }

    function getRelatedIds ($movie)
    {
        $relatedIds = CacheFilm::getListIds(

            config('cachekeys.relatedIds') . $movie['id'],

            function () use ($movie) {

                $catIds = [];

                foreach ($movie['categories'] as $cat)
                {
                    $catIds[] = $cat['id'];
                }

                $relatedIds = Movie::join('anhi_movie_movie_category as mc', 'anhi_movie_movies.id', '=', 'mc.movie_id')
                            ->where("anhi_movie_movies.type", $movie['type'])
                            ->where('anhi_movie_movies.id', '<>', $movie['id'])
                            ->whereIn('mc.category_id', $catIds)
                            ->skip(0)
                            ->take(10)
                            ->select('anhi_movie_movies.id')
                            ->get()
                            ->lists('id');

                return $relatedIds;
            }

        );

        return $relatedIds;
    }

    function getMostViewList ($type, $orderByField, $skip=0, $take=8)
    {

        $mostViewIds = $this->getMostViewIds($type, $orderByField, $skip, $take);

        return CacheFilm::getMovies($mostViewIds);
    }

    function getMostViewIds ($type, $orderByField, $skip, $take)
    {
        $mostViewIds = CacheFilm::getListIds(

            config('cachekeys.mostViewIds') . $type . '_' . $orderByField, 

            function () use ($type, $orderByField, $skip, $take) {

                $orderBy = [$orderByField, "desc"];

                $ids = Movie::where('type', $type)
                                ->orderBy(...$orderBy)
                                ->skip($skip)
                                ->take($take)
                                ->select('id')
                                ->get()->lists('id');

                return $ids;
            }
        );

        return $mostViewIds;
    }

    function getPlayFilmInfo ($movieId, $episode)
    {
        $url = '';

        $movie = CacheFilm::getMovie($movieId);

        if (!empty($movie['links']))
        {
            foreach ($movie['links'] as $link)
            {
                if ($link['episode'] == $episode)
                    $movieUrlInfo = $link;

            }
        }

        if (!empty($movieUrlInfo))
            $url = $movieUrlInfo['url'];

        $result = $this->getPlayLink($url);

        return $result;

    }

    private function getPlayLink ($url)
    {
        $IDLink =  explode("#", $url);

        if(empty($IDLink) || count($IDLink)!=2){
            return NULL;

        }
        
        $gid = $IDLink[1];

        $links = $this->getLinks($gid);

        if (!is_array($links))
            $links = [];

        $result = $links;

        return $result;

    }

    function findOrAddWatchInfo ($movieId,$episodeId)
    {
        $movie_episode_watch_key = 'movie_episode_watch_'.$movieId."_".$episodeId;

        if (Session::has($movie_episode_watch_key))
        {
            return session($movie_episode_watch_key);
        }

        // If User is not authenticated , Return 0
        $user = Auth::getUser();

        if ($user)
        {
            return 0;
        }

        // get episodes user watched

        $watchedinfo = Movie::find($movieId)
                            ->episodesWatched()
                            ->where('episode', $episodeId)
                            ->where('user_id', $user ? $user->id : -1)
                            ->first();


        if (!$watchedinfo && $user)
        {
            $watchedinfo = new MovieEpisodeWatched;
            $watchedinfo->movie_id = $movieId;
            $watchedinfo->episode = $episodeId;
            $watchedinfo->user_id = $user->userId;
            $watchedinfo->watch_time = 0;
            $watchedinfo->save();
        }

        session([$movie_episode_watch_key=> !empty($watchedinfo->watch_time) ? $watchedinfo->watch_time : 0]);

        return !empty($watchedinfo->watch_time) ? $watchedinfo->watch_time : 0;
    }

    function updateViewCount ($movieId)
    {
        Movie::where("id", $movieId )
                ->update([
                    "view" => DB::raw('view + 1'),
                    "view_in_day" => DB::raw('view_in_day + 1'),
                    "view_in_week" => DB::raw('view_in_week + 1'),
                    "view_in_months" => DB::raw('view_in_months + 1'),
                ]);
    }
    
    function buildSearchMovieQuery ($keyword)
    {
        $keyword = \DB::connection()->getPdo()->quote($keyword . '*');

        $keyword = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $keyword);
        
        return Movie::whereRaw("MATCH(name,english_name,actor,director,producer) AGAINST(? IN BOOLEAN MODE)
            ", [$keyword]);
    }

    public function searchMovies ($keyword, $getInfo=0, $skip=0, $limit=50)
    {

        $cacheKey = config('cachekeys.search') . $keyword . $getInfo . $skip;

        $film = Cache::remember($cacheKey, config('cache.expire'), function ()
            use ($keyword, $getInfo, $skip, $limit) {

            $movieIds = $this->buildSearchMovieQuery($keyword, $skip, $limit)
                            ->orderBy('name', 'asc')
                            ->skip($skip)
                            ->take($limit)
                            ->select('id')
                            ->get()->lists('id');

            $movies = CacheFilm::getMovies($movieIds);

            $film = [];

            if ($getInfo)
            {
                if ($movies)
                {
                    foreach ($movies as $movie)
                    {
                        $film[] = [
                            'name' => $movie['name'],
                            'image' => $movie['poster']['thumb'],
                            'link' => url($movie['friendly_url'] . "-" . $movie['id'] . "-info")
                        ];
                    }
                }
            }
            else
            {
                $film = $movies;
            }

            return $film;
        });

        return ['film' => $film];

    }

    function getUserRequestList ($skip, $take)
    {
        $list = $this->buildQueryUserRequestList($skip, $take)->get();

        return $list;
    }

    function buildQueryUserRequestList ($skip, $take)
    {
        $user = Auth::getUser();

        return MovieRequest::where('user_id', $user->id)
                            ->skip($skip)
                            ->take($take)
                            ->select("id", "link", "process_link", "date_add", "status");
    }

    function deleteMovieReport ($id)
    {
        return MovieReport::where('id', $id)->delete();
    }

    function deleteMovieRequest ($id)
    {
        return MovieRequest::where('id', $id)->delete();
    }

    function getUserReportList ($skip, $take)
    {
        $list = $this->buildQueryUserReportList($skip, $take)->get();

        return $list;
    }

    function buildQueryUserReportList ($skip, $take)
    {
        $user = Auth::getUser();

        return MovieReport::where('user_id', $user->id)
                            ->skip($skip)
                            ->take($take)
                            ->select("id", "movie_name", "link", "movie_error", "audio_error", "description", "date_add", "status");
    }
}