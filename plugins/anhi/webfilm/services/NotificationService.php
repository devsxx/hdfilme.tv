<?php

namespace Anhi\WebFilm\Services;

use Anhi\Movie\Models\Notification;

use DB, Auth;

class NotificationService
{
	function getWebNotifications ()
	{

		$user = Auth::getUser();

		if ($user)
		{
			$userId = $user->id;

			$to = [$userId, 'all'];

			$userGroup = $user->groups->first();

			if ($userGroup)
				$to[] = $userGroup->code;

			$query = \DB::table('anhi_movie_notifications_users');

			$notifications = Notification::whereIn('to', $to)
								->select('id','title','content','redirect_url','created_at')
								->orderBy('id','desc')
								->get();
			
			$values = [];

			foreach ($notifications as $noti)
			{
				$values[] = "({$userId}, $noti->id, 1, now())";
			}

			$values = implode(',', $values);

			if (!empty($values))
			{
				try {

					\DB::statement("insert ignore anhi_movie_notifications_users(user_id, notification_id, web_noti_status, created_at) values {$values}");

				} catch (\Exception $ex) {
					info('Failed to insert notifications');
					info($ex->getMessage());
				}
			}

			return $notifications;

		}

		return [];
		
	}

	function countNewWebNotifications ()
	{
		$user = Auth::getUser();

		if ($user)
		{
			$userId = $user->id;

			$userGroup = $user->groups->first();

			$to = "({$userId}, 'all')";

			if ($userGroup)
				$to = "({$userId}, '{$userGroup->code}', 'all')";

			$count = \DB::table('anhi_movie_notifications as n')
						->leftJoin('anhi_movie_notifications_users as nu', function ($join) use ($userId) {
							$join->on('n.id', '=', 'nu.notification_id');
							$join->where('nu.user_id', '=', $userId);
						})
						->whereRaw("n.to in {$to} and nu.user_id is null")
						->count();

			return $count;
		}

		return 0;
		
	}
}