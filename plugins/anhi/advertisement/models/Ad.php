<?php namespace Anhi\Advertisement\Models;

use Model;

/**
 * Model
 */
class Ad extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'anhi_advertisement_ads';
}