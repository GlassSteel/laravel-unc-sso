<?php echo '<?php' ?> namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class {{ $model_class }} extends Model implements AuthenticatableContract
{

	use Authenticatable, \GlassSteel\LaravelUncSso\UncSsoTrait {
		\GlassSteel\LaravelUncSso\UncSsoTrait::getRememberToken insteadof Authenticatable;
		\GlassSteel\LaravelUncSso\UncSsoTrait::getRememberTokenName insteadof Authenticatable;
		\GlassSteel\LaravelUncSso\UncSsoTrait::setRememberToken insteadof Authenticatable;
	}

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
	];

}//class {{ $model_class }}