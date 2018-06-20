<?php namespace Anhi\Coupon\Models;

use Model;

use Crisu83\ShortId\ShortId;

use Anhi\Coupon\Models\Code;
/**
 * Model
 */
class CouponCode extends Model
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

     public $hasMany = [


        'codes' => ['Anhi\Coupon\Models\Code', 'key' => 'coupon_id', 'otherKey' => 'id'],
    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'anhi_coupon_coupons';

    function afterCreate ()
    {
        $this->insertCode();
    }

    function insertCode ()
    {

        $shortid = ShortId::create();

        $insertCodes = [];

        for ($i = 0; $i < $this->quantity; $i++)
        {
            $insertCodes[] = [
                'code' =>  $shortid->generate(),
                'coupon_id' => $this->id,
                'created_at' => (new \DateTime)->format('Y-m-d h:i:s'),
            ];
        }

        $result = Code::insert($insertCodes);

        if (!$result)
            throw new AjaxException("Failed to create codes");
            
    }

    function afterDelete ()
    {
        Code::where('coupon_id', $this->id)->delete();
    }
}