<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests\Command;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractCommandMessage;
use SonsOfPHP\Bridge\Symfony\Cqrs\Tests\DummyCommand;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractCommandMessage
 */
final class AbstractCommandMessageTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $command = $this->getMockForAbstractClass(AbstractCommandMessage::class);

        $this->assertInstanceOf(CommandMessageInterface::class, $command); // @phpstan-ignore-line
    }

    /**
     * @covers ::__construct
     * @covers ::configureOptions
     * @covers ::getOptions
     * @covers ::getOption
     */
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
