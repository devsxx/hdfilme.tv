<?php namespace Anhi\Payment\Models;

use Model;

/**
 * Model
 */
class PaymentPackages extends Model
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
    public $table = 'anhi_payment_packages';


    public $hasOne = [
        'paygate' => [
            'Anhi\Payment\Models\Paygate',
            'key' => 'code',
            'otherKey' => 'paygate_code'
        ]
    ];
}