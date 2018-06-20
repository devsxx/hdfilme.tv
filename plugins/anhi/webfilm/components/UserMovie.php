<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper, Auth;

use Anhi\Shared\Services\UserService;

class UserMovie extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
    {
        return [
            'name' => 'User Favorite or Watch Later movies',
            'description' => 'Displays a collection of movies'
        ];
    }

    function onRender ()
    {

        $type = $this->property('type');

        $userService = new UserService;

        $data = $userService->movie($type);

        $this->page['type'] = $type;

        if (isset($data['movies']))
            $this->page['listMovie'] = $data['movies'];
        else
            $this->page['listHistory'] = $data['listHistory'];

        $this->page['paging'] = $data['paging'];

        $assets = '/plugins/anhi/webfilm/assets';
        
        $isMobile =  Helper::isMobile();

        if (!$isMobile)
            $this->addJs($assets . '/js/poster_slider.js');
    }
}