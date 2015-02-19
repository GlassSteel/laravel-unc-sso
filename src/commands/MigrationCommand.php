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
        $this->laravel->view->addNamespace('unc_sso', substr(__DIR__, 0, -8).'views');

        $keysTable = Config::get('unc_sso.unc_keys_table');
        $localusersTable = Config::get('unc_sso.local_users_table');
        
        $this->line('');
        $this->info( "Tables: $keysTable" );

        $message = "A migration that creates the '$keysTable' table will be created in app/database/migrations directory";

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]")) {

            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration($keysTable)) {
                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Coudn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }

            $this->line('');

        }
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
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_unc_sso_setup_tables.php";

        $usersTable  = Config::get('auth.table');
        $userModel   = Config::get('auth.model');
        $userKeyName = (new $userModel())->getKeyName();

        $data = compact('keysTable','localusersTable');

        $output = $this->laravel->view->make('unc_sso::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }
}
