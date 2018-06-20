<?php namespace Anhi\Movie\Models;

use Model;

/**
 * Model
 */
class DownloadFile extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $connection = 'upload_tracking';

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'download_files';

}