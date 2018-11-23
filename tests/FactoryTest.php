<?php declare(strict_types=1);

namespace ApiClients\Tools\CommandBus;

use ApiClients\Tools\TestUtilities\TestCase;
use DI\ContainerBuilder;
use React\EventLoop\LoopInterface;

/**
 * @internal
 */
final class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $container = ContainerBuilder::buildDevContainer();
        $container->set(LoopInterface::class, $this->prophesize(LoopInterface::class)->reveal());
        $this->assertInstanceOf(CommandBus::class, Factory::create($container));
    }
}
