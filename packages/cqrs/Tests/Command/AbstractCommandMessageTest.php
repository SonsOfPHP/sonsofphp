<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests\Command;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\Command\AbstractCommandMessage;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageInterface;
use SonsOfPHP\Component\Cqrs\Tests\DummyCommand;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AbstractCommandMessageTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $command = $this->getMockForAbstractClass(AbstractCommandMessage::class);

        $this->assertInstanceOf(CommandMessageInterface::class, $command); // @phpstan-ignore-line
    }

    public function testConstructor(): void
    {
        DummyCommand::setConfigureOptionsCallback(function ($resolver) {
            $resolver->define('id');
        });
        $cmdOptions = [
            'id' => 'unique-id',
        ];
        $command = new DummyCommand($cmdOptions);

        $this->assertArrayHasKey('id', $command->getOptions());
        $this->assertSame('unique-id', $command->getOption('id'));
    }
}
