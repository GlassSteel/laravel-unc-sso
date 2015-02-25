<?php namespace GlassSteel\LaravelUncSso;

use Closure, App, Auth;

class UncAuthUser {

	public function handle($request, Closure $next)
	{
		return \UNCSSO::checkAuthUser( $next($request) );
	}

}//class UncAuthUser
