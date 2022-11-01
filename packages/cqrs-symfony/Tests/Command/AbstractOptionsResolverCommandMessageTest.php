<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests\Command;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use SonsOfPHP\Bridge\Symfony\Cqrs\Tests\DummyCommand;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage
 */
final class AbstractOptionsResolverCommandMessageTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $command = $this->getMockForAbstractClass(AbstractOptionsResolverCommandMessage::class);

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
        DummyCommand::setConfigureOptionsCallback(function ($resolver): void {
            $resolver->define('id');
        });
        $cmdOptions = [
            'id' => 'unique-id',
        ];
        $command = new DummyCommand($cmdOptions);

        $this->assertArrayHasKey('id', $command->getOptions());
        $this->assertSame('unique-id', $command->getOption('id'));
    }

    /**
     * @covers ::__get
     * @covers ::getOption
     */
    public function testMagicMethodGetWorks(): void
    {
        DummyCommand::setConfigureOptionsCallback(function ($resolver): void {
            $resolver->define('id');
        });
        $command = new DummyCommand([
            'id' => 'unique-id',
        ]);

        $this->assertSame('unique-id', $command->id);
    }

    /**
     * @covers ::__isset
     * @covers ::hasOption
     */
    public function testMagicMethodIssetWorks(): void
    {
        DummyCommand::setConfigureOptionsCallback(function ($resolver): void {
            $resolver->define('id');
        });
        $command = new DummyCommand([
            'id' => 'unique-id',
        ]);

        $this->assertTrue(isset($command->id));
    }
}
