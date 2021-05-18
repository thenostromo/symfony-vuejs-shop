<?php
declare(strict_types=1);

namespace Deployer;

set('default_stage', 'test');

require 'recipe/symfony.php';

set('application', 'Ranked Choice');

set('repository', 'git@github.com:thenostromo/Symfony5CourseShop');

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts');

add('shared_files', ['.env.local']);
add('shared_dirs', ['public/uploads']);

host('test')
    ->hostname('localhost')
    ->user('thenostromo')
    ->port(22)
    ->stage('test')
    ->set('branch', 'develop')
    ->set('deploy_path', '~/deploy-folder')
;

// Tasks
task('pwd', function (): void {
    $result = run('pwd');
    writeln("Current dir: {$result}");
});

// Build yarn locally
task('deploy:build:assets', function (): void {
    run('npm install');
    run('npm encore production');
})->local();

// Upload assets
task('upload:assets', function (): void {
    upload(__DIR__.'/public/build/', '{{release_path}}/public/build');
});

after('deploy:build:assets', 'upload:assets');

after('deploy:failed', 'deploy:unlock');
