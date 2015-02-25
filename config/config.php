<?php

/**
 * This file is part of UNC SSO,
 * a basic Shibboleth solution for Laravel.
 *
 * @license MIT
 * @package GlassSteel\LaravelUncSso
 */
return array(
    'signup_action' => URL::to('/admin/signup'),//TEMP action('UncSsoController@signup'),

    'spoof_as' => [
        'pid' => null,
    ],
    
    'unc_keys_table' => 'unc_keys',
    'local_users_table' => 'local_users',

    'unc_key_model' => 'App\UncKey',
    'local_user_model' => 'App\LocalUser',

    'seeder_class' => 'UncSsoDevUserSetupSeeder',
    'dev_user' => [
    	'first_name' => 'Paul',
    	'last_name' => 'L\'Astnamé',
    	'email' => 'fake@fake.net',
    	'onyen' => 'harpoon',
    	'unc_pid' => '765432189',
    ],

    'auth_model' => Config::get('auth.model'),
    'auth_table' => Config::get('auth.table'),
);
