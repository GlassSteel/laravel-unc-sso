<?php namespace GlassSteel\LaravelUncSso;

use Config;

trait UncSsoTrait
{

	public function UncKeyRel(){
		return $this->hasOne('App\UncKey');
	}//UncKeyRel()

	public function hasUncKey(){
		return ( $this->UncKeyRel ) ? true : false;
	}//hasUncKey()

	public function getUncPid(){
		if ( $this->hasUncKey() ){
			return $this->UncKeyRel->unc_pid;
		}
		return false;
	}//getUncPid()

	public function getOnyen(){
		if ( $this->hasUncKey() ){
			return $this->UncKeyRel->onyen;
		}
		return false;
	}//getOnyen()

	public static function findByPid($unc_pid){
		$this_class = get_called_class();
		return $this_class::whereHas( 'UncKeyRel', function($q) use ($unc_pid){
			$q->where('unc_pid','like',$unc_pid);
		} )->first();
	}//findByPid()

	public function getRememberToken(){ return null; }
	public function setRememberToken($value){}
	public function getRememberTokenName(){ return null; }

	public function setAttribute($key, $value){
		$isRememberTokenAttribute = $key == $this->getRememberTokenName();
		if (!$isRememberTokenAttribute){
	    	parent::setAttribute($key, $value);
	    }
	}//setAttribute()

}//trait UncSsoTrait