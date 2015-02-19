<?php namespace GlassSteel\LaravelUncSso;

class UncSso
{

	public function get_pid(){
		return '1234567';
		/* do in config
		if ( !is_null($this->spoof_pid) ){
			return $this->spoof_pid;
		}
		*/

		if ( App::environment() == 'local' ){
		
			if ( $last_local_user = LocalUser::orderby('id','desc')->first() ){
				if ( $user_acct = User::find($last_local_user->user_id) ){
					$pid = $user_acct->unc_pid;
				}
			}
		
		}else{

			if( isset($_SERVER['pid']) && $_SERVER['pid'] ){
				$pid = $_SERVER['pid'];
			}
		
		}
		return false;
	}//get_pid()

}//class UncSso
