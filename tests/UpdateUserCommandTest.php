<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('api:update-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'id' => 1,
            'name' => 'user-02',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString(
            "User has been updated!
======================
User ID: 1
User name: user-02
",
            $output
        );
    }
}