<?php

namespace Anhi\Shared\Services;

use Auth, Input, DB, Hash, Response;

use Anhi\WebFilm\Services\FilmService;

use Helper;

class UserService
{
	function editUserInfo ()
	{
		$updateData = [];

        $error = [];

        $user = Auth::getUser();

        if (!empty($user))
        {
	        if (!empty(Input::get("name", "")))
	            $updateData["name"] = Input::get("name");

	        $oldPassword = Input::get("password_old", "");
	        $newPassword = Input::get("password", "");
	        $confirmationPassword = Input::get("password_confirmation", "");

	        if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmationPassword))
	        {
	            if($newPassword == $confirmationPassword)
	            {
	                if (Hash::check($oldPassword, $user->password))
	                    $updateData["password"] = Hash::make($newPassword);
	                else
	                    $error["password_old"] = 'Falsches altes Password';

	            }
	            else
	            {
	                $error["password_confirmation"] = 'Das Bestätigunspassword muss dem eingegeben Password entsprechen';
	            }
	        }

	        if (!empty($updateData))
	        {
	            $query = \DB::connection("mysql")->table("user");
	            try {
	                DB::table('users')->where('id', $user->id)->update($updateData);
	            } catch (QueryException $e) {
	            }
	        }
	    }
	    else
	    {
	    	$error['not_login'] = 'Please log in.';
	    }

	    return $error;
	}

	function updateAvatar ()
	{
		$user = Auth::getUser();

		$file = Input::file('file');

		$user->avatar()->create(['data' => Input::file('file')]);

		return ['result' => 1, 'avatar' => $user->avatar->getPath()];
	}

	function deleteAvatar ()
	{
		$user = Auth::getUser();
		$user->avatar()->delete();

		return ['result' => 1, 'avatar' => '/themes/hdfilme/assets/img/default-avatar.jpg'];
	}

    private function removeFavourite ($type)
    {
        $movieId = Input::get("movieId", 0);
        
        if (Input::get("action", "") == "remove" && $movieId > 0)
        {
            $filmService = new FilmService;

            if ($type === 'watch-later')
                $result = $filmService->watchLater($movieId);
            
            $result = $filmService->favourite($movieId);
        }
    }

	public function movie($type)
	{

        $user = Auth::getUser();
        $movieService = new MovieService;

        if ($type == "favorite" || $type == "watch-later")
        {
        	$movieId = Input::get("movieId", 0);

            $this->removeFavourite($type);

            $result["img"] = "/theme/" . config("app.theme") . "/img/favorite_add.png";
            $page = Input::get("page", 1);
            $limit = Input::get("limit", 50);
            $from = (($page -1 ) *  $limit);

            if ($type == "watch-later")
                $table = "anhi_movie_movie_watch_later";
            else
                $table = "anhi_movie_movie_favourite";

            $movies = $movieService->getFavouriteMovies($user->id, $from, $limit, $table);

            $paging = Helper::paging(count($movies), $limit, $page);

            $data['movies'] = $movies;

            $data['paging'] = $paging;

            return $data;

        }
        else
        {
            $action = Input::get('action');
            $recordId = Input::get('recordId', 0);
            $user = Auth::getUser();

            if ($action == "remove" && $recordId > 0)
            {
                if ($type === 'report')
                    $movieService->deleteMovieReport($recordId);
                else
                    $movieService->deleteMovieRequest($recordId);
            }

            $page = Input::get("page", 1);
            $limit = Input::get("page", 50);
            $from = (($page -1 ) *  $limit);

            if ($type === 'report')
            {
                $query = $movieService->buildQueryUserReportList($from, $limit);
                $data["listHistory"] = $movieService->getUserReportList($from, $limit);
            }
            else
            {
                $query = $movieService->buildQueryUserRequestList($from, $limit);
                $data["listHistory"] = $movieService->getUserRequestList($from, $limit);
            }
            
            $paging = Helper::paging($query->count(), $limit, $page);
            $data['paging'] = $paging;

            return $data;
        }
    }
}