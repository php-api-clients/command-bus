<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

/**
 * @internal
 */
final class Cache
{
    public static function read(string $cacheFile): iterable
    {
        return json_try_decode(file_get_contents($cacheFile));
    }

    public static function write(string $cacheFile, array $mapping): bool
    {
        return (bool)file_put_contents($cacheFile, json_encode($mapping));
    }
}
