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
            'name' => 'user-01',
            'email' => 'user-01@email.com',
            'groups' => 1,
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString(
            "User has been created!
======================
User ID: 1
User name: user-01
User email: user-01@email.com
",
            $output
        );
    }
}