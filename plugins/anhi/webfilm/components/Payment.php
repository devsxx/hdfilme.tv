<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Anhi\Payment\Models\Paygate;

use Helper;

class Payment extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
    {
        return [
            'name' => 'Payment',
            'description' => 'Payment'
        ];
    }

    function onRender ()
    {
    	$this->page['listPaygate'] = Paygate::where('active', 1)->get();
    }
}