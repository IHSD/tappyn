<?php

require 'recipe/common.php';

set('shared_dirs', ['application/cache', 'application/logs']);
set('writeable_dirs', ['application/cache', 'application/logs']);
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

set('repository', 'git@github.com:IHSD/tappyn.git');

task('deploy:config', function() {
    run('cp /var/www/tappyn/shared/config/database.php {{release_path}}/application/config/database.php');
    // run('cp /var/www/tappyn/shared/config/facebook_ion_auth.php {{release_path}}/application/config/facebook_ion_auth.php');
    // run('cp /var/www/tappyn/shared/config/config.php {{release_path}}/application/config/config.php');
    // run('cp /var/www/tappyn/shared/config/interest.php {{release_path}}/application/config/interest.php');
    // run('cp /var/www/tappyn/shared/config/secrets.php {{release_path}}/application/config/secrets.php');
})->desc("Set configuration");

task('deploy:migrate', function() {
    run('cp /var/www/tappyn/shared/config/phinx.yml {{release_path}}/application/phinx.yml');
    run('cd {{release_path}}/application && php vendor/bin/phinx migrate');
})->desc("Run migrations")->onlyOn('app-01');

task('deploy:vendors', function() {
    run('cd {{release_path}}/application && composer install');
})->desc("Install composer dependencies");

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:config',
    'deploy:vendors',
    'deploy:migrate',
    'deploy:shared',
    'deploy:symlink',
    'cleanup'
])->desc('Deploy your project');

after('deploy', 'success');
