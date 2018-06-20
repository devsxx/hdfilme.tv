<?php namespace Anhi\Coupon\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

use Request, Auth;

use Anhi\Coupon\Models\Code;


use Anhi\Coupon\Models\CouponCode as Coupon;

class CodeController extends Controller
{
    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    function updateUserDays ($days)
    {
        $user = Auth::getUser();

        $expire = new \DateTime($user->expire);

        $now = new \DateTime;

        if ($expire < $now)
            $expire = $now;

        $expire->modify("+{$days} days");

        $user->expire = $expire;

        return $user->save();
    }

    function checkCode ()
    {

        $user = Auth::getUser();

        if (empty($user))
            return redirect('/login');

    	$code = Request::get('code');

        $codeInfo = Code::where('code', $code)->get()->first();

        $result = ['success' => false, "msg" => ''];

        if (!$codeInfo)
        {
            $result['msg'] = '"' . $code .'" does not exist';
        }
        else if ($codeInfo->user_email)
        {
            $result['msg'] = '"' . $code .'" has been used';
        }
        else
        {

            $currentTime = new \DateTime;

            $from = new \DateTime($codeInfo->coupon->from);

            $to = new \DateTime($codeInfo->coupon->to);

            if ($currentTime >= $from
                && $currentTime <= $to
                && $codeInfo->coupon->status)
            {


                $days = $codeInfo->coupon->days;

                $updateUserDays = $this->updateUserDays($days);

                if ($updateUserDays)
                {   
                    Code::where('id', $codeInfo->id)->update(['user_email' => $user->email]);

                    $result['success'] = true;
                    $result['msg'] = "You have more {$days} addtional days.";
                }
                else
                {
                    $result['msg'] = "Something went wrong. Your days has not updated. Please try again.";
                }
            }
            else
            {
                $result['msg'] = 'Coupon Code is invalid';
            }
        }


        return redirect('/')->with(["couponResult" => $result]);
    }
}