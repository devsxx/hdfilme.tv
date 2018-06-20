<?php namespace Anhi\Movie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Anhi\Movie\Models\MovieErrorReport as Model;
use Request;
use Anhi\Movie\Models\Notification;
use Helper;

class MovieErrorReport extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\ReorderController'];
    
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Movie', 'movie');
    }

    function onUpdateMovieErrorReport ()
    {
        $field = Request::input('field');

        $id = Request::input('id');

        $movieErrReport = Model::find($id);

        if (empty($movieErrReport))
            throw new Exception("Data not found");

        $movieErrReport->{$field} = 1;

        if ($movieErrReport->save())
        {
            if (!$movieErrReport->send_notification)
            {
                $sent = Helper::sendNotification(
                    $movieErrReport->user_id,
                    'Movie Error Report is fixed',
                    "Your error report about film: {$movieErrReport->movie_name} has been fixed",
                    $movieErrReport->link
                );

                if ($sent)
                {

                    $movieErrReport->send_notification = 1;
                    $movieErrReport->save();
                }
            }
        }

        return json_encode(1);

    }
}