<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper;

class AntiBlock extends \Cms\Classes\ComponentBase
{
  
	public function componentDetails()
    {
        return [
            'name' => 'AntiBlock',
            'description' => ''
        ];
    }

    function onRender ()
    {
    	$isVip = Helper::isVip();

		if (!$isVip)
		{
			$this->page['antiBlock'] = (include_once ( public_path().'/pa_antiadblock.php'));
		}
    }
}