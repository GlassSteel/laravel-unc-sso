<?php namespace GlassSteel\LaravelUncSso;

use Closure, App, Auth;

class UncInitUser {

	public function handle($request, Closure $next)
	{
		\UNCSSO::setAuthUser();
		return $next($request);
	}

}//class UncInitUser
