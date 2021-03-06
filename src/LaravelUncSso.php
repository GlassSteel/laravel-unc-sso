<?php namespace GlassSteel\LaravelUncSso;

use Illuminate\Support\Facades\Config, App, Auth, Request, Redirect, Url;

class LaravelUncSso
{

	public function setAuthUser(){
		$user = false;
		$pid = $this->get_pid();
		if ( $pid && $this->validate_pid($pid) ){
			$user_class = Config::get('unc_sso.auth_model');
			$user = $user_class::findByPid($pid);
		}
		if ( $user ){
			Auth::login($user);
		}elseif ( Auth::check() ){
			Auth::logout();
		}
	}//setAuthUser()

	public function checkAuthUser($next){
		if ( !Auth::check() ){
			$this->setAuthUser();
		}
		if ( !Auth::check() ){
			$pid = $this->get_pid();
			if ( $pid && $this->validate_pid($pid) ){
				/**
				 * Assume PID is valid, but no matching User yet in our DB;
				 * Redirect to signup screen
				 */
				$signup = Url::get( Config::get('unc_sso.signup_action') );
				if ( Request::url() != $signup ){
					return Redirect::to( $signup );
				}
			}else{
				/**
				 * PID is missing or malformed -> go home
				 */
				if (!Request::is('/')){
					return Redirect::to('/');
				}
			}
		}
		return $next;
	}//checkAuthUser()

	public function get_pid(){
		$spoof_pid = Config::get('unc_sso.spoof_as')['pid'];
		if ( $spoof_pid && !is_null($spoof_pid) ){
			return $spoof_pid;
		}
		if ( App::environment() == 'local' ){
			if ( $last_local_user = App\LocalUser::orderby('id','desc')->first() ){
				$user_class = Config::get('unc_sso.auth_model');
				if ( $user_acct = $user_class::find($last_local_user->user_id) ){
					return $user_acct->getUncPid();
				}
			}
		}else{
			if( isset($_SERVER['pid']) && $_SERVER['pid'] ){
				return $_SERVER['pid'];
			}
		}
		return false;
	}//get_pid()

	public function validate_pid($pid){
		if (	$pid 								//not falsey
				&& preg_match('/^[0-9]*$/', $pid) 	//all numerals
				&& strlen($pid) == 9 && 			//9 digits
				$pid[0] == '7' )					//starts with 7
		{
			return true;
		}
		return false;
	}//validate_pid()

}//class LaravelUncSso
