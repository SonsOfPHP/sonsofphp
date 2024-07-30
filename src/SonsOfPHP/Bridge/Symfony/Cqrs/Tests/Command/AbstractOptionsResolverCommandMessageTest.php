<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use SonsOfPHP\Bridge\Symfony\Cqrs\Tests\DummyCommand;
use SonsOfPHP\Contract\Cqrs\CommandMessageInterface;

/**
 * @uses \SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage
 * @coversNothing
 */
#[CoversClass(AbstractOptionsResolverCommandMessage::class)]
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
