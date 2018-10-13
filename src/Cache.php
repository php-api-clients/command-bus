<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ExceptionalJSON\DecodeErrorException;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

/**
 * @internal
 */
final class Cache
{
    public static function read(string $cacheFile): iterable
    {
        try {
            $mapping = json_try_decode(file_get_contents($cacheFile));
            foreach ($mapping as $command => $handler) {
                yield $command => new Handler($handler);
            }
        } catch (DecodeErrorException $decodeErrorException) {
            // Do nothing, we'll return an empty array instead
        }

        return [];
    }

    public static function write(string $cacheFile, array $mapping): void
    {
        file_put_contents($cacheFile, json_encode(array_map(function (Handler $handler) {
            return [
                $handler->getHandler(),
            ];
        }, $mapping)));
    }
}
