<?php

namespace Anhi\Userprofile\Components;

use Helper, Auth, Session;

class UserInfo extends \Cms\Classes\ComponentBase
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
    	$this->page['registrationSuccess'] = Session::pull("registrationSuccess");

    	$this->page['created'] = date("d-m-Y", Auth::getUser()->created);

    	$this->page['edit'] = $this->property('edit');
    }

}