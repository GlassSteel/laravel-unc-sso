<?php echo '<?php' ?> namespace App;

use Illuminate\Database\Eloquent\Model;

class {{ $model_class }} extends Model
{

	protected $fillable = [
		'onyen',
		'unc_pid',	
	];

	public function UserRel(){
		return $this->hasOne('{{ $user_class }}');
	}

}//class {{ $model_class }}