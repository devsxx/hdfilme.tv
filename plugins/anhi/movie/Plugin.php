<?php namespace Anhi\Movie;

use System\Classes\PluginBase;

use Helper;

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

    		'Anhi\Movie\FormWidgets\FilmStarts' => [
    			'label' => 'Filmstarts',
    			'code' => 'film_starts'
    		],

    		'Anhi\Movie\FormWidgets\Country' => [
    			'label' => 'Country',
    			'code' => 'country'
    		],

    		'Anhi\Movie\FormWidgets\Select2box' => [
    			'label' => 'Select2 Box',
    			'code' => 'select2box'
    		],
    	];
    }

    public function registerListColumnTypes()
    {
        return [
            // A local method, i.e $this->evalUppercaseListColumn()
            'servertype' => [$this, 'getServerName'],
            'movietype' => [$this, 'getMovieTypeName']

        ];
    }

    public function getServerName ($value, $column, $record)
    {
        $servers = Helper::movieServer();

        return !empty($servers[$value]) ? $servers[$value] : '';
    }

    public function getMovieTypeName ($value, $column, $record)
    {
        $types = Helper::movieTypes();

        return !empty($types[$value]) ? $types[$value] : '';
    }
}
