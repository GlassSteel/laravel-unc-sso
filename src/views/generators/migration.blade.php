<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UncSsoSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Modify Laravel 5's default users table
        Schema::table('users', function($table)
        {
            $table->dropColumn(['name', 'password', 'remember_token']);
            $table->string('first_name')->nullable();
            $table->string('last_name');
        });

        // Create table for storing unc pids and onyens (unc keys)
        Schema::create('{{ $keysTable }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->string('onyen')->unique();
            $table->string('unc_pid')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('{{ $userKeyName }}')->on('{{ $usersTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        // Create table for designating local user in absence of actual Shibboleth
        Schema::create('{{ $localusersTable }}', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('{{ $userKeyName }}')->on('{{ $usersTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $keysTable }}');
        Schema::drop('{{ $localusersTable }}');
    }
}
