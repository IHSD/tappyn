<?php

require 'recipe/common.php';

if(!file_exists('.deploy_args.php'))
{
    echo "No .deploy_args.php file found, using defaults\n";
    $public_key_file = '~/.ssh/id_rsa';
    echo "Deploying with public key located in {$public_key_file} as default\n";
} else {
    require '.deploy_args.php';
    echo "Deploying with public key located in {$public_key_file} as specified in .deploy_args.php\n";
}
/**
 * Set our shared and writeable directories. This is where all the log, cache and
 * release shared files should be placed
 */

set('shared_dirs', ['public/api/v1/application/cache', 'public/api/v1/application/logs']);
set('writeable_dirs', ['public/api/v1/application/cache', 'public/api/v1/application/logs']);
set('repository', 'git@github.com:IHSD/tappyn.git');

env('deploy_path', '/var/www/tappyn');
server("tappyn-live", "tappyn.com", 22)
    ->user('deploy')
    ->identityFile($public_key_file, $private_key_file)
    //->identifyFile()
    ->env('environment', 'production')
    ->stage('production')
    ->env('branch', 'master');

server("tappyn-staging", 'test.tappyn.com', 22)
    ->user('deploy')
    ->identityFile($public_key_file, $private_key_file)
    //->identityFile()
    ->stage('staging')
    ->env('environment', 'testing')
    ->env('branch', 'master');


// Copy our production configuration to our new release directory
task('deploy:config', function() {
    run('cp {{deploy_path}}/shared/config/v1/{{environment}}/* {{release_path}}/public/api/v1/application/config/{{environment}}');
    run('cp {{deploy_path}}/shared/config/phinx.yml {{release_path}}/phinx.yml');
    run('cp {{deploy_path}}/shared/config/config.js {{release_path}}/public/config.js');
})->desc('Adding configuration');

// Install any vendor requirements
task('deploy:vendor', function() {
    run('cd {{release_path}} && composer install --no-dev');
    run('cd {{release_path}} && npm install');
})->desc('Installing dependenies');

// Gulp our JS/CSS files
task('deploy:build', function() {
    run('cd {{release_path}} &&  npm run build');
})->desc("Compiling JS/CSS");

task('deploy:post_update', function() {
    run('chmod +x {{release_path}}/bin/backup.sh');
    run('chown -R deploy:www-data {{deploy_path}}');
})->desc("Setting up backup process");

// Run database migrations. This depends on both config and vendor
task('deploy:migrate', function() {
    run('cd {{release_path}} && php vendor/bin/phinx migrate');
})->desc("Running migrations");

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:post_update',
    'deploy:config',
    'deploy:vendor',
    'deploy:build',
    'deploy:migrate',
    'deploy:symlink',
    'cleanup'
])->desc('Deploy your project');

after('deploy', 'success');
