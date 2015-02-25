<?php echo '<?php' ?> namespace App;

use Illuminate\Database\Eloquent\Model;

class {{ $model_class }} extends Model
{

	public $timestamps = false;
	
	public function UserRel(){
		return $this->hasOne('{{ $user_class }}');
	}

}//class {{ $model_class }}