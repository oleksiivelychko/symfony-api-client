<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ListUsersInGroupsCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('api:list-group');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'id' => 1,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}