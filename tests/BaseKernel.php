<?php

namespace Tests;

use Console\CreateGroupCommand;
use Console\CreateUserCommand;
use Console\DeleteGroupCommand;
use Console\DeleteUserCommand;
use Console\ListUsersInGroupsCommand;
use Console\UpdateGroupCommand;
use Console\UpdateUserCommand;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class BaseKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
    }

    protected function configureContainer(ContainerBuilder $container): void
    {
        $container->register(CreateGroupCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(UpdateGroupCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(DeleteGroupCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(CreateUserCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(UpdateUserCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(DeleteUserCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register(ListUsersInGroupsCommand::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->register('logger', NullLogger::class);

        $container->loadFromExtension('framework', [
            'secret' => 'secret',
            'test' => true,
            'router' => ['utf8' => true],
            'secrets' => false,
        ]);
    }
}