#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$application = new Application('Console App', 'v1.0.0');

// ...register commands
$application->add(new \Console\CreateUserCommand());
$application->add(new \Console\UpdateUserCommand());
$application->add(new \Console\DeleteUserCommand());
$application->add(new \Console\CreateGroupCommand());
$application->add(new \Console\UpdateGroupCommand());
$application->add(new \Console\DeleteGroupCommand());
$application->add(new \Console\ListUsersInGroupsCommand());

try {
    $application->run();
} catch (Exception $e) {
    print_r($e);
}