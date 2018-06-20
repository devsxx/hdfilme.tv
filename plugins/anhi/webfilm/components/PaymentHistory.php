<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper, Request;

use Anhi\Shared\Services\PaymentService;

class PaymentHistory extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'User Payment History',
            'description' => 'User\'s Payment History'
        ];
    }

    function onRender ()
    {
    	$paymentService = new PaymentService;

    	$page = Request::input('page', 1);

    	$perPage = Request::input('perPage', 10);

    	$this->page['histories'] = $paymentService->getHistories($page, $perPage);

    	$this->page['paging'] = $paymentService->paging($page, $perPage);
    }
}