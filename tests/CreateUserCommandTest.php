<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('api:create-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'name' => 'user',
            'email' => 'user'.time().'@email.com',
            'groups' => [1,2],
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}