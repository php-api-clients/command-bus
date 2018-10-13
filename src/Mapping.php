<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use WyriHaximus\Tactician\CommandHandler\Mapper;

/**
 * @internal
 */
final class Mapping
{
    public static function resolve(array $directories, ?string $cacheFile): iterable
    {
        if ($cacheFile !== null && file_exists($cacheFile)) {
            $commandToHandlerMap = Cache::read($cacheFile);
            if (count($commandToHandlerMap) > 0) {
                return $commandToHandlerMap;
            }
        }

        $commandToHandlerMap = self::gather($directories);

        if ($cacheFile !== null && is_dir(dirname($cacheFile))) {
            Cache::write($cacheFile, $commandToHandlerMap);
        }

        return $commandToHandlerMap;
    }

    private static function gather(array $directories): iterable
    {
        $map = [];
        foreach ($directories as $path => $namespace) {
            $map += Mapper::map($path, $namespace);
        }

        return $map;
    }
}
