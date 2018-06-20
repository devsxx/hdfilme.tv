<?php namespace Anhi\Movie\Models;

use Model;

/**
 * Model
 */
class Producer extends Model
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
    public $table = 'anhi_movie_producers';

    public $belongsToMany = [
        'movies' => 'Anhi\Movie\Models\Movie'
    ];
}