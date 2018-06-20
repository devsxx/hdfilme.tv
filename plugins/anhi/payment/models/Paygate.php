<?php namespace Anhi\Payment\Models;

use Model;

/**
 * Model
 */
class Paygate extends Model
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
    public $table = 'anhi_payment_paygates';

    // public $belongsTo = [
    //     'package' => 'Anhi\Payment\Models\Package',
    //     'key' => 'paygate_code',
    //     'otherKey' => 'code'
    // ];

    public $hasMany = [
        'packages' => ['Anhi\Payment\Models\PaymentPackages', 'key' => 'paygate_code', 'otherKey' => 'code'],
    ];
}