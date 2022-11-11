<?php

namespace Victtech\OssPlugin\Facades;

use Illuminate\Support\Facades\Facade;

class OssPlugin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ossplugin';
    }
}
