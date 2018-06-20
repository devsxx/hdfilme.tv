<?php

namespace Anhi\Shared\Services\Payments;

use Auth, Input, DB, Hash, Response;

use Request;

use Anhi\Payment\Models\WebMoneyTransaction;

use Anhi\Payment\Models\PaymentPackages as Package;

use Anhi\Payment\Models\PaymentTransaction;

use Anhi\Shared\Services\PaymentService;

class Paypal extends PaymentService
{
    function getId ($data)
    {
        return $data['id'];
    }
}