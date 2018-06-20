<?php namespace Anhi\Movie\Models;

use Model;

/**
 * Model
 */
class MovieLink extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'anhi_movie_movie_links';

    function beforeSave ()
    {
        if (empty($this->minute))
            $this->minute = 0;
    }

    function beforeCreate ()
    {
        $this->pushed_download_queue = 0;
    }

    function beforeUpdate ()
    {
        $this->pushed_download_queue = 0;
    }
}