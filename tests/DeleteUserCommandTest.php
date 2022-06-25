<?php

namespace Tests;

use Console\BaseCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DeleteUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('api:delete-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'id' => 1,
        ]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            BaseCommand::SUCCESSFUL_OP,
            $commandTester->getDisplay()
        );
    }
}