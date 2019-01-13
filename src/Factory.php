<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use Composed\Package;
use function Composed\packages;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Psr\Container\ContainerInterface;
use React\EventLoop\LoopInterface;
use function WyriHaximus\getIn;
use function WyriHaximus\iteratorOrArrayToArray;

final class Factory
{
    public static function create(ContainerInterface $container, array $options = [])
    {
        $commandToHandlerMap = self::resolveMapping($options[Options::COMMAND_HANDLER_MAP_CACHE_FILE] ?? null);
        $commandToHandlerMap = iteratorOrArrayToArray($commandToHandlerMap);

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

    private static function resolveMapping(?string $cacheFile): iterable
    {
        yield from Mapping::resolve(self::resolveDirectories(), $cacheFile);
    }

    private static function resolveDirectories()
    {
        /** @var Package $package */
        foreach (packages() as $package) {
            $config = $package->getConfig('extra');

            if ($config === null) {
                continue;
            }

            $mapping = getIn($config, 'api-clients.command-bus');

            if ($mapping === null) {
                continue;
            }

            yield $package->getPath($mapping['path']) => $mapping['namespace'];
        }
    }
}
