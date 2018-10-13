<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ApiClients\Tools\TestUtilities\TestCase;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use React\EventLoop\Factory;
use function Clue\React\Block\await;
use function React\Promise\resolve;

final class CommandBusTest extends TestCase
{
    public function testHandle()
    {
        $loop = Factory::create();

        $command = new class('t-dog') {
            private $t;

            public function __construct(string $t)
            {
                $this->t = $t;
            }

            public function getT(): string
            {
                return $this->t;
            }
        };

        $commandToHandlerMap = [
            get_class($command) => new class() {
                public function handle($command)
                {
                    return resolve($command);
                }
            },
        ];

        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($commandToHandlerMap),
            new HandleInflector()
        );

        $commandBus = new CommandBus($loop, $handlerMiddleware);

        $this->assertSame(
            $command,
            await(
                $commandBus->handle($command),
                $loop
            )
        );
    }
}
