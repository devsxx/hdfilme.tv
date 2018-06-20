<?php namespace Anhi\Payment;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets ()
    {
    	return [

    		'Anhi\Payment\FormWidgets\SelectRelation' => [
    			'label' => 'Select Relation',
    			'code' => 'selectrelation'
    		],
    	];
    }
}
