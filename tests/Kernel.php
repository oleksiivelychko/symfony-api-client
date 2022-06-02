<?php

namespace Tests;

use Console\CreateGroupCommand;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
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

        $container->register('logger', NullLogger::class);

        $container->loadFromExtension('framework', [
            'secret' => 'secret',
            'test' => true,
            'router' => ['utf8' => true],
            'secrets' => false,
        ]);
    }
}