<?php

namespace Anhi\Shared;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	public function register()
    {
        $this->app->bind('helper', function ($app) {

		    return new \Anhi\Shared\Helper;

		});

		$this->app->bind('cacheService', function ($app) {

		    return new \Anhi\Shared\Services\CacheService;

		});
    }
}