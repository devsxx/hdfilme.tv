<?php namespace Anhi\Payment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Transaction extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Payment', 'payment', 'transactions');
    }
}