<?php

namespace Anhi\Userprofile\Controllers;

use Backend\Classes\Controller;

use Input, Cache, Auth, Hash, DB, Response;

use Anhi\Shared\Services\UserService;

class UserController extends Controller
{

	public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

	function editInfo ()
	{
        $userService = new UserService;

        $error = $userService->editUserInfo();

        return redirect()->back()
            ->withErrors($error);
    }

    function updateAvatar ()
    {
    	$userService = new UserService;

        return Response::json($userService->updateAvatar());
    }

    function deleteAvatar ()
    {
    	$userService = new UserService;

        return Response::json($userService->deleteAvatar());
    }
}