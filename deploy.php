<?php

require 'recipe/common.php';

/**
 * Set our shared and writeable directories. This is where all the log, cache and
 * release shared files should be placed
 */
set('shared_dirs', ['application/cache', 'application/logs']);
set('writeable_dirs', ['application/cache', 'application/logs']);

/**
 * Setup our server farm
 */
/*----------------
Production
----------------*/
server("app-01", "104.236.199.116", 22)
    ->user('root')
    ->identityFile("~/.ssh/id_rsa.pub", "~/.ssh/id_rsa")
    ->stage('production')
    ->env('deploy_path', "/var/www/tappyn");
server('app-02', "104.236.207.136", 22)
    ->user('root')
    ->identityFile("~/.ssh/id_rsa.pub", "~/.ssh/id_rsa")
    ->stage('production')
    ->env('deploy_path', '/var/www/tappyn');
server('dev-staging', "162.243.60.183", 22)
    ->user('deployer')
    ->identityFile()
    ->stage('staging')
    ->env('deploy_path', "/var/www/tappyn");

/* Set the repository URL */
set('repository', 'git@github.com:IHSD/tappyn.git');

/*
 * Copy over all of our configuration files
 */
task('deploy:config', function() {
    run('cp {{deploy_path}}/shared/config/database.php {{release_path}}/application/config/database.php');
    run('cp /var/www/tappyn/shared/config/facebook_ion_auth.php {{release_path}}/application/config/facebook_ion_auth.php');
    run('cp /var/www/tappyn/shared/config/config.php {{release_path}}/application/config/config.php');
    run('cp /var/www/tappyn/shared/config/interest.php {{release_path}}/application/config/interest.php');
    run('cp /var/www/tappyn/shared/config/secrets.php {{release_path}}/application/config/secrets.php');
})->desc("Set configuration");

/*
 * Run migrations
 *
 * This should only execute from one server
 */
task('deploy:migrate', function() {
    run('cp {{deploy_path}}/shared/config/phinx.yml {{release_path}}/application/phinx.yml');
    run('cd {{release_path}}/application && php vendor/bin/phinx migrate');
})->desc("Run migrations");

/*
 * Build compiled files
 */
task('deploy:build', function() {
    run('cd {{deploy_path}} && gulp js');
})->desc('Build files');

/*
 * Composer functions
 */

task('deploy:vendors', function() {
    run('cd {{release_path}}/application && composer install');
})->desc("Install composer dependencies");

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:config',
    'deploy:vendors',
    'deploy:build',
    'deploy:migrate',
    'deploy:shared',
    'deploy:symlink',
    'cleanup'
])->desc('Deploy your project');

task('stage', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:config',
    'deploy:vendors',
    'deploy:build',
    'deploy:migrate',
    'deploy:shared',
    'deploy:symlink',
    'cleanup'
    ])->desc('Push to QA');
after('deploy', 'success');
