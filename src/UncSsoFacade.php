<?php namespace GlassSteel\LaravelUncSso;

use Illuminate\Support\Facades\Facade;

class UncSsoFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
    	return 'unc_sso';
    }

}//class UncSsoFacade