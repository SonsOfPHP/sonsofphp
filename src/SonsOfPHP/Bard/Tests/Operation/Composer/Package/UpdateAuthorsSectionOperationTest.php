<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Composer\Package;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Composer\Package\UpdateAuthorsSectionOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(UpdateAuthorsSectionOperation::class)]
final class UpdateAuthorsSectionOperationTest extends TestCase
{
    private UpdateAuthorsSectionOperation $worker;

    private JsonFileInterface&MockObject $rootFile;

    private JsonFileInterface&MockObject $packageFile;

    protected function setUp(): void
    {
        $this->rootFile = $this->createMock(JsonFileInterface::class);
        $this->worker = new UpdateAuthorsSectionOperation($this->rootFile);
        $this->packageFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillUpdateAuthorsSection(): void
    {
        $authors = [
            [
                'name'     => 'Sons Of PHP',
                'homepage' => 'https://sonsofphp.com',
            ],
        ];
        $this->rootFile->method('getSection')->willReturn($authors);

        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'authors',
                $this->callback(fn($value): true => $value === $authors)
            )
        ;

        $this->worker->apply($this->packageFile);
    }
}
