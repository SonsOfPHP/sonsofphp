<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageMetadata;
use SonsOfPHP\Component\EventSourcing\Message\MessagePayload;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[CoversClass(MessageNormalizer::class)]
#[UsesClass(AbstractMessage::class)]
#[UsesClass(MessageMetadata::class)]
#[UsesClass(MessagePayload::class)]
final class MessageNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new MessageNormalizer();

        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    public function testItWillNormalizeMessage(): void
    {
        $normalizer = new MessageNormalizer();

        $message = new class extends AbstractMessage {};

        $this->assertTrue($normalizer->supportsNormalization($message));

        $output = $normalizer->normalize($message);

        $this->assertArrayHasKey('payload', $output);
        $this->assertArrayHasKey('metadata', $output);
    }
}
