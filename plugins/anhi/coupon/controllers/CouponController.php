<?php namespace Anhi\Coupon\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

use Request;

use Anhi\Coupon\Models\Code;


use Anhi\Coupon\Models\CouponCode as Coupon;

class CouponController extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Coupon', 'menu-coupon');
    }

    function downloadCodes ()
    {

    	$couponId = Request::input('id', -1);

    	$result = Code::where('coupon_id', $couponId)->get();

    	$coupon = Coupon::where('id', $couponId)->select("name")->first();

    	header("Content-disposition: attachment; filename={$coupon->name}.csv");

		header('Content-type: text/csv');

		$content = 'STT,Code,User' . PHP_EOL;

        $index = 1;

		foreach ($result as $line)
		{
			$content .= "{$index},{$line->code},{$line->user_email}," . PHP_EOL;

            $index++;
		}

		echo $content;
    }

}