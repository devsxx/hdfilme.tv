<?php

namespace Anhi\Userprofile\Components;

use Helper, Auth, Session;

class UserNav extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Banner Slider',
            'description' => 'Displays a collection of movies as a slider'
        ];
    }


    function onRender ()
    {
    	$this->page['className'] = $this->property('className', '');
    }

}