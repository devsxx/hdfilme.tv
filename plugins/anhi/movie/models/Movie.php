<?php namespace Anhi\Movie\Models;

use Model, DB;

use Anhi\Movie\Models\MovieLink;

/**
 * Model
 */
class Movie extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    private $episodesString = '';

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'anhi_movie_movies';

    public $attachOne = [
        'poster' => 'Anhi\Movie\Models\File',
        'banner' => 'Anhi\Movie\Models\File',
    ]; 


    public $belongsToMany = [

        'actors' => ['Anhi\Movie\Models\Actor', 'table' => 'anhi_movie_movie_actor'],

        'producers' => ['Anhi\Movie\Models\Producer', 'table' => 'anhi_movie_movie_producer'],

        'directors' => ['Anhi\Movie\Models\Director', 'table' => 'anhi_movie_movie_director'],

        'categories' => ['Anhi\Movie\Models\Category', 'table' => 'anhi_movie_movie_category'],
        
        'tags' => ['Anhi\Movie\Models\Tag', 'table' => 'anhi_movie_movie_tag'],
    ];

    public $hasMany = [


        'links' => ['Anhi\Movie\Models\MovieLink', 'key' => 'movie_id', 'otherKey' => 'id'],

        'favourites' => ['Anhi\Movie\Models\MovieFavourite', 'key' => 'movie_id', 'otherKey' => 'id'],

        'watchLaters' => ['Anhi\Movie\Models\MovieWatchLater', 'key' => 'movie_id', 'otherKey' => 'id'],

        'episodesWatched' => ['Anhi\Movie\Models\MovieEpisodeWatched', 'key' => 'movie_id', 'otherKey' => 'id'],
    ];

    public $hasOne = [
        'country' => ['Anhi\Movie\Models\Country', 'key' => 'id', 'otherKey' => 'country_id']
    ];

    function beforeCreate ()
    {

        $this->created_at = (new \DateTime)->format('Y-m-d h:i:s');

        $this->handleForeignColumn('actor', 'actors', 'actor_name');

        $this->handleForeignColumn('producer', 'producers', 'producer_name');

        $this->handleForeignColumn('director', 'directors', 'director_name');

        $this->user_id = \BackendAuth::getUser()->id;

        $this->top = time();

    }

    function beforeUpdate ()
    {
        $this->updated_at = (new \DateTime)->format('Y-m-d h:i:s');
    }

    function beforeSave ()
    {

        $this->episodesString = $this->episodes;
        unset($this->episodes);

        if ($this->onTop)
            $this->top = time();

        unset($this->onTop);

        if ($this->slide)
            $this->slide = time();


    }

    function afterSave ()
    {
        $this->handleMultiEpisodesInput();
    }

    private function handleMultiEpisodesInput ()
    {

        $episodes = preg_split('/\\r\\n|\\n|\\r/', $this->episodesString);

        foreach ($episodes as $episode)
        {
            $episodeInfo = explode(',', $episode);

            if (count($episodeInfo) !== 4)
                continue;

            $episodeNo = trim($episodeInfo[0]);

            if (intval($episodeNo) < 0)
                continue;

            $episodeShow = trim($episodeInfo[2]);

            if (empty($episodeShow))
                $episodeShow = $episodeNo;

            $url = trim($episodeInfo[1]);
            $minute = trim($episodeInfo[3]);

            $minute = empty($minute) ? 0 : $minute;

            MovieLink::insert([
                'movie_id' => $this->id,
                'episode' => $episodeNo,
                'episode_show' => $episodeShow,
                'url' => $url,
                'minute' => $minute
            ]);
        }

    }
    
    private function handleForeignColumn ($column, $foreignColumn, $valueFrom)
    {
        $value = '';

        foreach ($this->{$foreignColumn} as $item)
        {
            $value .=  ',' . $item->{$valueFrom};
        }

        $value = substr($value, 1);
        
        $this->{$column} = $value;
    }
}