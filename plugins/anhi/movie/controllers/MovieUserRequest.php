<?php namespace Anhi\Movie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Request;
use Anhi\Movie\Models\MovieUserRequest as Model;
use Helper;

class MovieUserRequest extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\ReorderController'];
    
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Movie', 'movie');
    }


    function onUpdateMovieRequest ()
    {

    	$id = Request::input('id');

    	$movieRequest = Model::where('id', $id)->first();

    	if (empty($movieRequest))
            throw new \Exception("Data not found");

        $movieRequest->process_link = Request::input('process_link', '');

        $movieRequest->status = 1;
    	
    	if ($movieRequest->save())
        {
            if (!$movieRequest->send_notification)
            {
                $sent = Helper::sendNotification(
                    $movieRequest->user_id,
                    'Movie Request is resolved',
                    "Your movie request: {$movieRequest->movie_name} has been resolved",
                    '/user/movie/request'
                );

                if ($sent)
                {
                    $movieRequest->send_notification = 1;
                    $movieRequest->save();
                }
            }
        }

        return json_encode(['status' => 1]);
    }
}