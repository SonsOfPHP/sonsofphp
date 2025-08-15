<?php

declare(strict_types=1);

namespace Chorale\Tests\IO;

use Chorale\IO\JsonReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonReporter::class)]
#[Group('unit')]
#[Small]
final class JsonReporterTest extends TestCase
{
    public function testBuildReturnsPrettyPrintedJsonWithNewline(): void
    {
        $jr = new JsonReporter();
        $json = $jr->build(['a' => 'b'], ['new' => []], [['action' => 'none']]);
        self::assertStringEndsWith("\n", $json);
    }

    #[Test]
    public function testBuildIncludesDefaultsKey(): void
    {
        $jr = new JsonReporter();
        $json = $jr->build(['a' => 'b'], ['new' => []], [['action' => 'none']]);
        self::assertStringContainsString('"defaults"', $json);
    }
}
