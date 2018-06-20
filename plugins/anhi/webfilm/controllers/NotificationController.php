<?php

namespace Anhi\WebFilm\Controllers;

use Backend\Classes\Controller;

use Input, Cache, Auth, Request;

use Anhi\WebFilm\Services\NotificationService;

class NotificationController extends Controller
{
	public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    function getWebNotifications ()
    {
    	$service = new NotificationService;

    	$notifications = $service->getWebNotifications();

    	return json_encode($notifications);
    }

    function countNewWebNotifications ()
    {
    	$service = new NotificationService;

    	$count = $service->countNewWebNotifications();

    	return json_encode(['count' => $count]);
    }
}