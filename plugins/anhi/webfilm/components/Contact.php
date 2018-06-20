<?php

namespace Anhi\WebFilm\Components;

use Anhi\Movie\Models\Movie;

use Helper;

class BannerSlider extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Banner Slider',
            'description' => 'Displays a collection of movies as a slider'
        ];
    }
}