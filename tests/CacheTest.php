<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ApiClients\Tools\TestUtilities\TestCase;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Handlers\AwesomesauceHandler;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

final class CacheTest extends TestCase
{
    public function testIO()
    {
        $tmpFile = $this->getTmpDir() . bin2hex(random_bytes(13)) . '.json';
        $mapping = [
            AwesomesauceCommand::class => new Handler([
                AwesomesauceHandler::class,
            ]),
        ];

        Cache::write($tmpFile, $mapping);
        self::assertFileExists($tmpFile);

        $mappingFromCache = iterator_to_array(Cache::read($tmpFile));
        self::assertCount(1, $mappingFromCache);
        self::assertTrue(isset($mappingFromCache[AwesomesauceCommand::class]));
        self::assertInstanceOf(Handler::class, $mappingFromCache[AwesomesauceCommand::class]);
        self::assertSame(AwesomesauceHandler::class, $mappingFromCache[AwesomesauceCommand::class]->getHandler());
    }

    public function testReadNonExistent()
    {
        $tmpFile = $this->getTmpDir() . bin2hex(random_bytes(13)) . '.json';
        touch($tmpFile);
        self::assertCount(0, Cache::read($tmpFile));
    }
}
