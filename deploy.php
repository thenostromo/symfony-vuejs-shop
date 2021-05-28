<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/symfony.php';

set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('bin/console', '{{bin/php}} {{release_path}}/bin/console');

set('application', 'Ranked Choice');

set('repository', 'git@github.com:thenostromo/Symfony5CourseShop');

set('default_stage', 'test');

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts');

set('keep_releases', 3);

add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log', 'public/uploads']);
add('writable_dirs', ['var', 'public/uploads']);

host('35.229.119.165')
    ->hostname('35.229.119.165')
    ->port(22)
    ->user('redlesleys')
    ->become('deployer')
    ->identityFile('~/.ssh/id_rsa.pub')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->stage('production')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/ranked-choice.com')
;

// Tasks
task('pwd', function (): void {
    $result = run('pwd');
    writeln("Current dir: {$result}");
});
/*
set('clear_paths', [
    './README.md',
    './.gitignore',
    './.git',
    './.php_cs',
    './.env.dist',
    './.env',
    './.eslintrc.js',
    '/assets',
    '/node_modules',
    '/tests',
    './package.json',
    './package-lock.json',
    './symfony.lock',
    './webpack.config.js',
    './phpunit.xml',
    './phpunit.xml.dist',
    './deploy.php',
]);
*/
set('env', function () {
    return [
        'APP_ENV' => 'prod',
        'DATABASE_URL' => 'postgresql://rc_admin:Uc@Td127m%r6Jrl@34.74.105.34:5432/ranked_choice?serverVersion=12.5&charset=utf8',
        'COMPOSER_MEMORY_LIMIT' => '512M',
    ];
});

// Build yarn locally
task('deploy:build:assets', function (): void {
    //run('npm install');
    //run('npm encore production');
})->local();

desc('Build CSS/JS and deploy local built files');
task('deploy:build_local_assets', function () {
    // runLocally('npm install');
    // runLocally('npm run build');
    upload('./public/build', '{{release_path}}/public/.');
    upload('./public/bundles', '{{release_path}}/public/.');
});
/*
task('deploy:assets:install', function () {});
task('deploy:cache:clear', function () {
    run('{{bin/console}} cache:clear --no-warmup');
});*/
/*
desc('Build CSS/JS remotely');
task('deploy:build_remote_assets', function() {
    run("cd {{release_path}} && npm install && npm run build");
});

after('deploy:update_code', 'deploy:build_remote_assets');*/
after('deploy:update_code', 'deploy:build_local_assets');

after('deploy:failed', 'deploy:unlock');

after('deploy', 'success');
