<?php namespace Anhi\Advertisement;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
    		'Anhi\Advertisement\Components\Ad' => 'ad',
    	];
    }

    public function registerSettings()
    {
    }
}
