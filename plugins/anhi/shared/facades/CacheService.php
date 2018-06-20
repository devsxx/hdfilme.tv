<?php

namespace Anhi\Shared\Facades;

use Illuminate\Support\Facades\Facade;

class CacheService extends Facade
{
	protected static function getFacadeAccessor() { return 'cacheService'; }
}