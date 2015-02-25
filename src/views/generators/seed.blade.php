<?php echo '<?php' ?>

use Illuminate\Database\Seeder;

class {{ $seeder_class }} extends Seeder {

    public function run(){
        $dev_user = {{ $userModel }}::firstOrCreate([
            'first_name' => '{{ $dev_user['first_name'] }}',
            'last_name' => '{{ $dev_user['last_name'] }}',
            'email' => '{{ $dev_user['email'] }}',
        ]);
        if ( $dev_user ){
            $unc_key = {{ $unc_key_model }}::firstOrCreate([
                'user_id' => $dev_user->id,
                'onyen' => '{{ $dev_user['onyen'] }}',
                'unc_pid' => '{{ $dev_user['unc_pid'] }}',
            ]);

            $local_user = {{ $local_user_model }}::firstOrCreate([
                'user_id' => $dev_user->id,
            ]);
        }
    }

}