<?php

namespace Anhi\WebFilm\Components;

use Indikator\News\Models\Posts as NewsPost;

use Helper, Cache;

class News extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
    {
        return [
            'name' => 'News List',
            'description' => 'News List'
        ];
    }

    function buildViewCacheKey ()
    {
        $mobile = Helper::isMobile() ? '_mobile' : '';

        $cacheKey = config('cachekeys.views.news') . $mobile;

        if (Helper::isVip())
            $cacheKey .= '_vip_';

        return $cacheKey . Helper::mobilePrefix();
    }

    function onRender ()
    {
        return Cache::remember($this->buildViewCacheKey(), config('cache.view_expire'), function () {

            $view = $this->renderPartial('@default.htm', [
                'newsList' => NewsPost::skip(0)->take(50)->get()
            ]);

            return $view;
        });
    }
}