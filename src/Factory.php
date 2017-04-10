<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use Composed\Package;
use function Composed\packages;
use function igorw\get_in;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Psr\Container\ContainerInterface;
use React\EventLoop\LoopInterface;
use WyriHaximus\Tactician\CommandHandler\Mapper;

final class Factory
{
    public static function create(ContainerInterface $container)
    {
        $commandToHandlerMap = self::gatherMapping();

        $containerLocator = new ContainerLocator(
            $container,
            $commandToHandlerMap
        );

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $containerLocator,
            new HandleInflector()
        );

        return new CommandBus(
            $container->get(LoopInterface::class),
            $commandHandlerMiddleware
        );
    }

    private static function gatherMapping()
    {
        $map = [];
        /** @var Package $package */
        foreach (packages() as $package) {
            $config = $package->getConfig('extra');

            if ($config === null) {
                continue;
            }

            $mapping = get_in(
                $config,
                [
                    'api-clients',
                    'command-bus'
                ]
            );

            if ($mapping === null) {
                continue;
            }

            $map += Mapper::map($package->getPath($mapping['path']), $mapping['namespace']);
        }

        return $map;
    }
}
