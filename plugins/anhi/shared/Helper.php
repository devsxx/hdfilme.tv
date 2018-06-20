<?php

namespace Anhi\Shared;

use Auth, Cache;

use Anhi\Shared\UserHelperTrait;

use Anhi\Movie\Models\Notification;

use Request;

class Helper
{

    use UserHelperTrait;

    function mobilePrefix()
    {
        if (Request::is('mobile/*') || Request::is('mobile'))
            return '/mobile' . '/';

        return '/';
    }

    function sendNotification ($userId, $title, $content, $url)
    {
        try {

            $id = Notification::insertGetId([
                'to' => $userId,
                'title' => $title,
                'content' => $content,
                'redirect_url' => $url
            ]);


            // \DB::table('anhi_movie_notifications_users')->insert([
            //     'user_id' => $userId,
            //     'notification_id' => $id,
            //     'created_at' => (new \DateTime)->format('Y-m-d h:i:s')
            // ]);

        } catch (\Exception $ex) {

            info('Failed to send notification');
            info($ex->getMessage());

            return false;

        }

        return true;
    }

    function getClientIP ()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    function paging ($total, $limit, $page)
    {
        $paging = [];

        $paging["total"] = $total;

        $paging["startPage"] = $total > 0 ? 1 : 0;

        $paging["endPage"] = $total > 0 ? ceil($total/$limit) : 0;

        $paging["currentPage"] = empty($total) ? 0 : $page;

        $paging["nextPage"] = $paging["currentPage"] > 0 
                                && $paging["endPage"] > 0
                                && $page<$paging["endPage"] ? $page + 1 : 0;

        $paging["previousPage"] = $paging["currentPage"] > 0
                                    && $paging["startPage"] > 0
                                    && $page > $paging["startPage"] ? $page - 1 : 0;

        return $paging;
    }

	public function isMobile() {

        // Check Session

        if (Request::is('mobile/*') || Request::is('mobile'))
            return true;

        if(isset($_GET['mode_display']) )
            session(['mode_display'=>$_GET['mode_display']]);

        if (session('mode_display')=='mobile')
            return true;

        if (session('mode_display')=='desktop')
            return false;

        if(!isset($_SERVER['HTTP_USER_AGENT']))
        {
            #User agent not found , Desktop version
            return false;
        }

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $mobileReg = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i';
        $mobile = false;

        if(preg_match($mobileReg,$useragent))
        {
            $mobile = true;
        }

        return $mobile;
    }

    public function makeSeo($text, $limit=75)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if(strlen($text) > 70) {
            $text = substr($text, 0, 70);
        }

        if (empty($text))
        {
            //return 'n-a';
            return time();
        }

        return $text;
    }

    public function curl ($url)
    {
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }

    public function url($uri, $params = [])
    {
        $path =  \Route::getCurrentRoute()->getPath();

        $sub_path=substr( $path, 0, 7 ); 
       
        if($sub_path=="mobile" || $sub_path=="mobile/")
        {
            return url("mobile/{$uri}", $params);
        }

        return url($uri, $params);
    }

    public function forgetCache ($key, $tags)
    {
        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore)
            return Cache::tags($tags)->forget($key);

        return Cache::forget($key);
    }

    public function movieTypes() {
        return array(
            1 => "Movie",
            2 => "TV Show",
            3 => "Trailer",
        );
    }
    public function movieServer() {
        return array(
            "3" => "All Server (JW)",
            "7" =>"All Server (JS)",
            "13" => "All Server (Flow)",
            "2" => "Youtube",
            "12" => "FIX # TEN.PHIM",
            "14" => "VFIX # TEN.PHIM",
        );
    }
    public function movieQuality() {
        return [
            "0" => "Cam",
            "1" => "SD",
            "2" => "HD",
            "3" => "BLURAY 1080p",
            "4" => "BLURAY 720p",
            "5" => "HD-WEB",
            "6" => "Hard-sub",
            "7" => "TS",
        ];
    }
    public function audioQuality() {
        return array(
            "-1" => array(
                "id" => "-1",
                "name" => "Không",
                "default" => 1
            ),
            "0" => array(
                "id" => "0",
                "name" => "Mic",
            ),
        );
    }
    public function movieSetOrNot() {
        return array(
            "0" => array(
                "code" => 0,
                "name" => "Không"
            ),
            "-1" => array(
                "code" => -1,
                "name" => "Có"
            ),
        );
    }
    public function movieSet() {
        return array(
            "1" => array(
                "code" => 1,
                "name" => "Ẩn"
            ),
            "0" => array(
                "code" => 0,
                "name" => "Hiện"
            ),
        );
    }
    public function status() {
        return array(
            "1" => array(
                "code" => 1,
                "name" => "Đã xử lý"
            ),
            "0" => array(
                "code" => 0,
                "name" => "Chưa xử lý"
            ),
        );
    }
    public function activate() {
        return array(
            "1" => array(
                "code" => 1,
                "name" => "Đã kích hoạt"
            ),
            "0" => array(
                "code" => 0,
                "name" => "Chưa kích hoạt"
            ),
        );
    }
    public function block() {
        return array(
            "1" => array(
                "code" => 1,
                "name" => "Đã khóa"
            ),
            "0" => array(
                "code" => 0,
                "name" => "Không khóa"
            ),
        );
    }
}