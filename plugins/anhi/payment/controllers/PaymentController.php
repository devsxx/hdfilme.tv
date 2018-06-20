<?php namespace Anhi\Payment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

use Request;

use Anhi\Shared\Services\Payments\Paypal as PalpalService;
use Anhi\Shared\Services\Payments\BitPay as BitPayService;

class PaymentController extends Controller
{
    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    function storePaypalTransctionInfo ()
    {
    	$data = Request::all();

    	$paymentService = new PalpalService;

    	$result = $paymentService->storeTransaction($data);

    	return json_encode($result);
    }

    function createBitPayInvoice ()
    {
    	$bitPayService = new BitPayService;

    	$invoice = $bitPayService->createInvoice();

    	return redirect($invoice->getUrl());
    }

    private function cacheInvoiceId ($invoiceId)
    {
        Cache::put($invoiceId, Request::input('packageId'), 15);
    }

    function handleBitPaySuccessfulTransaction ()
    {
    	$data = Request::all();

        $data['packageId'] = Cache::pull($data['id']);

        $bitPayService = new BitPayService;

        $result = $bitPayService->storeTransaction($data);

        return json_encode($result);
    }
}
