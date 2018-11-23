<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use PackageVersions\Versions;
use function WyriHaximus\iteratorOrArrayToArray;
use WyriHaximus\Tactician\CommandHandler\Mapper;

/**
 * @internal
 */
final class Mapping
{
    public static function resolve(iterable $directories, ?string $cacheFile): iterable
    {
        if ($cacheFile !== null && file_exists($cacheFile)) {
            try {
                return Cache::read($cacheFile);
            } catch (\Throwable $et) {
                // void
            }
        }

        $commandToHandlerMap = self::gather($directories);

        if ($cacheFile !== null && is_dir(dirname($cacheFile))) {
            $commandToHandlerMap = iteratorOrArrayToArray($commandToHandlerMap);
            Cache::write($cacheFile, $commandToHandlerMap);
        }

        return $commandToHandlerMap;
    }

    private static function gather(iterable $directories): iterable
    {
        $mapperVersion = version_compare(
            explode(
                '@',
                Versions::getVersion('wyrihaximus/tactician-command-handler-mapper')
            )[0],
            '2.0.0',
            '<'
        );

        foreach ($directories as $path => $namespace) {
            $args = [$path];
            if ($mapperVersion) {
                $args[] = $namespace;
            }
            yield from Mapper::map(...$args);
        }
    }
}
