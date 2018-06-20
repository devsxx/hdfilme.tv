<?php namespace Anhi\Movie\Models;

use Model;
use RainLab\User\Models\UserGroup;
use RainLab\User\Models\User;
/**
 * Model
 */
class Notification extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'anhi_movie_notifications';


    // function afterSave ()
    // {
    //     if ($this->to === 'vip')
    //         $this->insertNotificationFor('vip');
    //     else if ($this->to === 'member')
    //         $this->insertNotificationFor('member');
    //     else
    //         $this->insertNotificationForEmail();            
    // }

    // function insertNotificationFor ($userGroupCode)
    // {
    //     $userGroupID = UserGroup::where('code', $userGroupCode)->first()->id;

    //     $userIds = \DB::table('users_groups')
    //                     ->where('user_group_id', $userGroupID)
    //                     ->lists('user_id');

    //     $insertData = [];

    //     foreach ($userIds as $userId)
    //     {
    //         $insertData[] = [
    //             'user_id' => $userId,
    //             'notification_id' => $this->id,
    //             'created_at' => (new \DateTime)->format('Y-m-d h:i:s')
    //         ];
    //     }

    //     if (!empty($insertData))
    //     {
    //         try {

    //             \DB::table('anhi_movie_notifications_users')->insert($insertData);

    //         } catch (\Exception $ex) {

    //             info('Error insert notification');
    //             info($ex->getMessage());
    //         }
    //     }
    // }
}