<?php namespace GlassSteel\LaravelUncSso;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'unc_sso:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the UNC SSO specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
      
        $this->laravel->view->addNamespace('unc_sso', __DIR__.'/views');

        $keysTable = Config::get('unc_sso.unc_keys_table');
        $localusersTable = Config::get('unc_sso.local_users_table');
        
        $this->line('');
        $this->info( "Tables: $keysTable" );

        $message = "A migration that creates the '$keysTable' table will be created in app/database/migrations directory";

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Create stub auth user model file? [Yes|no]") ){
            $this->line('');

            $this->info("Creating auth user model...");

            if ($this->createAuthUserStub()) {
                $this->info("Auth user model file successfully created!");
            } else {
                $this->error(
                    "Coudn't create model file.\n Check the write permissions".
                    " within the app/ directory."
                );
            }

            $this->line('');
        }
        
        if ($this->confirm("Proceed with the migration creation? [Yes|no]")) {

            $this->line('');

            $this->info("Creating migration...");

            if ($this->createMigration($keysTable,$localusersTable)) {
                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Coudn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }

            $this->line('');

        }

        if ($this->confirm("Create stub local user & unc key model files? [Yes|no]") ){
            $this->line('');

            $this->info("Creating models...");

            if ($this->createModelStubs()) {
                $this->info("Model files successfully created!");
            } else {
                $this->error(
                    "Coudn't create model files.\n Check the write permissions".
                    " within the app/ directory."
                );
            }

            $this->line('');
        }

        if ($this->confirm("Create seed file for development? [Yes|no]")) {

            $this->line('');

            $this->info("Creating seeder...");

            if ($this->createSeeder()) {
                $this->info("Seed File successfully created!");
            } else {
                $this->error(
                    "Coudn't create seed file.\n Check the write permissions".
                    " within the app/database/seeds directory."
                );
            }

            $this->line('');

        }
        
    }

    protected function createAuthUserStub(){
        $model_class = class_basename( Config::get('unc_sso.auth_model') );
        $output = $this->laravel->view->make('unc_sso::generators.auth_user_model')->with([
            'model_class' => $model_class,
        ])->render();
        $file = base_path("app") . "/" . $model_class . '.php';
        if ( file_exists($file) ){
            if ( !$this->confirm("Overrite existing $file file? [Yes|no]") ){
                return false;
            }else{
                if ( $fs = fopen($file, 'w')) {
                    fwrite($fs, $output);
                    fclose($fs);
                    return true;
                }
            }
        }else{
            if ( $fs = fopen($file, 'x')) {
                fwrite($fs, $output);
                fclose($fs);
                return true;
            }
        }
        
        return false;
    }//createAuthUserStub()

    /**
     * Create the plugin model stubs.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createModelStubs()
    {
        $key_model = class_basename( Config::get('unc_sso.unc_key_model') );
        $local_user_model = class_basename( Config::get('unc_sso.local_user_model') );
        $user_class = Config::get('unc_sso.auth_model');

        $key_output = $this->laravel->view->make('unc_sso::generators.key_model')->with([
            'model_class' => $key_model,
            'user_class'  => $user_class,
        ])->render();

        $local_users_output = $this->laravel->view->make('unc_sso::generators.local_user_model')->with([
            'model_class' => $local_user_model,
            'user_class'  => $user_class,
        ])->render();
        
        $key_model_file = base_path("app") . "/" . $key_model . '.php';
        $local_users_model_file = base_path("app") . "/" . $local_user_model . '.php';
        
        if (!file_exists($key_model_file) && $fs = fopen($key_model_file, 'x')) {
            fwrite($fs, $key_output);
            fclose($fs);
            if (!file_exists($local_users_model_file) && $fs = fopen($local_users_model_file, 'x')) {
                fwrite($fs, $local_users_output);
                fclose($fs);
            }
            return true;
        }

        return false;
    }

    /**
     * Create the migration.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createMigration($keysTable,$localusersTable)
    {
        $migrationFile = base_path("database/migrations")."/".date('Y_m_d_His')."_unc_sso_setup_tables.php";
        
        $usersTable  = Config::get('unc_sso.auth_table');
        $userModel   = Config::get('unc_sso.auth_model');
        $userKeyName = (new $userModel())->getKeyName();

        $data = compact('keysTable','localusersTable','userKeyName','usersTable');

        $output = $this->laravel->view->make('unc_sso::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Create the seed file.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createSeeder()
    {
        
        $seeder_class = Config::get('unc_sso.seeder_class');
        $userModel  = Config::get('unc_sso.auth_model');
        $dev_user = Config::get('unc_sso.dev_user');
        $local_user_model = Config::get('unc_sso.local_user_model');
        $unc_key_model = Config::get('unc_sso.unc_key_model');

        $data = compact('seeder_class','userModel','dev_user','local_user_model','unc_key_model');

        $output = $this->laravel->view->make('unc_sso::generators.seed')->with($data)->render();

        $seedFile = base_path("database/seeds")."/".$seeder_class.'.php';
        if (!file_exists($seedFile) && $fs = fopen($seedFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }
}
