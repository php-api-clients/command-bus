<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use React\Promise\CancellablePromiseInterface;

interface CommandBusInterface
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     *
     * @return CancellablePromiseInterface
     */
    public function handle($command): CancellablePromiseInterface;
}
