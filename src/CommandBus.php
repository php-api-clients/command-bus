<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use League\Tactician\CommandBus as Tactician;
use League\Tactician\Handler\CommandHandlerMiddleware;
use React\EventLoop\LoopInterface;
use React\Promise\CancellablePromiseInterface;
use function React\Promise\resolve;
use function WyriHaximus\React\futurePromise;

final class CommandBus implements CommandBusInterface
{
    /**
     * @var Tactician
     */
    private $commandBus;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @param LoopInterface            $loop
     * @param CommandHandlerMiddleware $commandHandlerMiddleware
     */
    public function __construct(LoopInterface $loop, CommandHandlerMiddleware $commandHandlerMiddleware)
    {
        $this->loop = $loop;
        $this->commandBus = new Tactician([
            $commandHandlerMiddleware,
        ]);
    }

    /**
     * Executes the given command and optionally returns a value.
     *
     * @param object $command
     *
     * @return CancellablePromiseInterface
     */
    public function handle($command): CancellablePromiseInterface
    {
        return futurePromise($this->loop, $command)->then(function ($command) {
            return resolve($this->commandBus->handle($command));
        });
    }
}
