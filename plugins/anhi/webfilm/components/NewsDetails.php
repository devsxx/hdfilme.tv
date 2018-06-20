<?php

namespace Anhi\WebFilm\Components;

use Indikator\News\Models\Posts as NewsPost;

use Helper, Cache;

class NewsDetails extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
    {
        return [
            'name' => 'News Details',
            'description' => 'News Details'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'description' => 'Slug path',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ]
        ];
    }

    function buildCacheKey ()
    {
        $mobile = Helper::isMobile() ? '_mobile' : '';

        $cacheKey = config('cachekeys.views.newsdetails') . $mobile;

        if (Helper::isVip())
            $cacheKey .= '_vip_';

        return $cacheKey . Helper::mobilePrefix();
    }

    function onRender ()
    {
        $slug = $this->property('slug');

        NewsPost::where('slug', $slug)->increment('statistics');

        return Cache::remember($this->buildCacheKey() . $slug, config('cache.view_expire'), function () use ($slug) {

            $view = $this->renderPartial('@default.htm', [
                'news' => NewsPost::where('slug', $slug)->first()
            ]);

            return $view;
        });
    }
}