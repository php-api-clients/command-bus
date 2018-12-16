<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ApiClients\Tools\TestUtilities\TestCase;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Handlers\AwesomesauceHandler;
use function WyriHaximus\iteratorOrArrayToArray;

/**
 * @internal
 */
final class MappingTest extends TestCase
{
    public function testResolve(): void
    {
        $mapping = iteratorOrArrayToArray(
            Mapping::resolve(
                [
                    \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR => 'Test\App',
                ],
                null
            )
        );

        self::assertSame(
            [
                AwesomesauceCommand::class => AwesomesauceHandler::class,
            ],
            $mapping
        );
    }
}
