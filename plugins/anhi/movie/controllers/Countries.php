<?php namespace Anhi\Movie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Countries extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Movie', 'movie');
    }

    // public function listExtendColumns ($list)
    // {
    //     $list->addColumns([
    //         "action" => [
    //             'label' => 'Hành động',
    //             'type' => 'partial',
    //             'sortable' => false,
    //         ]
    //     ]);
    // }
    // 
        
    function onMySubmit ()
    {
        info(Request::input('name'));

        return [
            [
                'id' => 1,
                'name' => 'test',
            ]
        ];
    }

}