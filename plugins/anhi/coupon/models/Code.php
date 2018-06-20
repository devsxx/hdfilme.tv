<?php namespace Anhi\Coupon\Models;

use Model;

/**
 * Model
 */
class Code extends Model
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
    public $table = 'anhi_coupon_codes';


    public $hasOne = [

        'coupon' => ['Anhi\Coupon\Models\CouponCode', 'key' => 'id', 'otherKey' => 'coupon_id']
    ];
}