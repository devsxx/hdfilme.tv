<?php

namespace Anhi\Advertisement\Components;

use Helper;

use Anhi\Advertisement\Models\Ad as AdModel;

class Ad extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
    {
        return [
            'name' => 'Advertisement',
            'description' => 'Displays a ads'
        ];
    }

    public function defineProperties()
	{
	    return [
	        'name' => [
	             'title'             => 'Ad name',
	             'description'       => 'Ad name',
	             'type'              => 'string',
	        ],
	        'height' => [
	             'title'             => 'The height of ad box',
	             'description'       => 'The height of ad box',
	             'type'              => 'number',
	        ],
	        'width' => [
	             'title'             => 'The width of ad box',
	             'description'       => 'The width of ad box',
	             'type'              => 'number',
	        ]
	    ];
	}

	function onRender ()
	{
		if (!Helper::isVip())
		{
			$name = $this->property('name');

			// $height = $this->property('height');

			// $width = $this->property('width');

			// $adService = new AdService;

			// $ad = $adService->getAd($name);
			
			$this->page['adObj'] = AdModel::where('name', $name)
											->where('active', 1)
											->first();
			$this->page['adName'] = $name;

			$this->page['cdn'] = config('upload.cdn_ad');

		}
	}
}