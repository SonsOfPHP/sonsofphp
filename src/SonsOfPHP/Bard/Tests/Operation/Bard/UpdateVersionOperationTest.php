<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Bard;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Bard\UpdateVersionOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;
use SonsOfPHP\Component\Version\VersionInterface;

#[Group('bard')]
#[CoversClass(UpdateVersionOperation::class)]
final class UpdateVersionOperationTest extends TestCase
{
    private UpdateVersionOperation $worker;

    private JsonFileInterface&MockObject $jsonFile;

    private VersionInterface&MockObject $version;

    protected function setUp(): void
    {
        $this->version = $this->createMock(VersionInterface::class);
        $this->worker  = new UpdateVersionOperation($this->version);

        $this->jsonFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillUpdateVersionSection(): void
    {
        $this->version->method('toString')->willReturn('1.2.3');
        $this->jsonFile->expects($this->once())->method('setSection')->with(
            'version',
            $this->callback(fn($version): true => '1.2.3' === $version)
        );

        $this->worker->apply($this->jsonFile);
    }
}
