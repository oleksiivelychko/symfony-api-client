<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateGroupCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('api:create-group');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'name' => 'group',
            'users' => [1,2]
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}