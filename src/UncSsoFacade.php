<?php namespace GlassSteel\LaravelUncSso\Facades;

use Illuminate\Support\Facades\Facade;

class UncSso extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'unc_sso'; }

}//class UncSso