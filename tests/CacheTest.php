<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ApiClients\Tools\TestUtilities\TestCase;
use Generator;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Handlers\AwesomesauceHandler;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;
use function WyriHaximus\iteratorOrArrayToArray;

/**
 * @internal
 */
final class CacheTest extends TestCase
{
    public function testIO(): void
    {
        $tmpFile = $this->getTmpDir() . \bin2hex(\random_bytes(13)) . '.json';
        $mapping = [
            AwesomesauceCommand::class => AwesomesauceHandler::class,
        ];

        $success = Cache::write($tmpFile, $mapping);
        self::assertTrue($success);
        self::assertFileExists($tmpFile);

        $mappingFromCache = iteratorOrArrayToArray(Cache::read($tmpFile));
        self::assertCount(1, $mappingFromCache);
        self::assertTrue(isset($mappingFromCache[AwesomesauceCommand::class]));
        self::assertSame(AwesomesauceHandler::class, $mappingFromCache[AwesomesauceCommand::class]);
    }

    public function testReadNonExistent(): void
    {
        $tmpFile = $this->getTmpDir() . \bin2hex(\random_bytes(13)) . '.json';
        \touch($tmpFile);

        $mappingFromCache = Cache::read($tmpFile);
        self::assertInstanceOf(Generator::class, $mappingFromCache);
        $mappingFromCache = iteratorOrArrayToArray($mappingFromCache);
        self::assertCount(0, $mappingFromCache);
    }
}
