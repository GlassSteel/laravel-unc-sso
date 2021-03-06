# LARAVEL UNC SSO (Laravel 5 Package)

A simple package for using UNC Single Sign On as user authorization for Laravel 5

## Installation

Laravel UNC SSO is not on Packagist. Add
```
{
	"type": "vcs",
	"url": "https://github.com/GlassSteel/laravel-unc-sso"
}
```
to your composer.json's `'repositories'` array, as well as `"glasteel/laravel-unc-sso" : "dev-master"` in your `'require'` array. Then run `composer install` or `composer update`.

Then in your `config/app.php` add 

    GlassSteel\LaravelUncSso\UncSsoServiceProvider::class
    
in the providers array. This package automatically registers 'UncSso' as a alias.

## Configuration

Copy the package config to your local config with the publish command:

```
php artisan vendor:publish
```

### User relation to roles

Now generate the package migration:

```bash
php artisan unc_sso:migration
```

It will generate the `<timestamp>_unc_sso_setup_tables.php` migration.
You may now run it with the artisan migrate command:

```bash
php artisan migrate
```

After the migration, two new tables will be present:
- `unc_keys` &mdash; stores onyens and unc PIDs
- `local_users` &mdash; is used to spoof users in testing

You may also opt to create stub models and a development seed file.

Add `$this->call('UncSsoDevUserSetupSeeder');` to `DatabaseSeeder.php` to use the seeder. You may also need to run `composer dump-autoload`

## Usage

Add `\GlassSteel\LaravelUncSso\UncInitUser::class` to the `$middleware` array in `app/http/Kernel.php` to check for a UNC pid on each request, and login the matching user, if any, via `Auth::login()`

Add `'unc_sso' => \GlassSteel\LaravelUncSso\UncAuthUser::class` to the `$routeMiddleware` array. Any routes protected by the `'unc_sso'` middleware will redirect to signup if a valid-seeming PID is found but not mached to an existing user, or will redirect to home if no PID is found.

```
//Simple test script
echo '<pre style="color:#000">';
print_r(Auth::user()->toArray());
echo '</pre>';
```