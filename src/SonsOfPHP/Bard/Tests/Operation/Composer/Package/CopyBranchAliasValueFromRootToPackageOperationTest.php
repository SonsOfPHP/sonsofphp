<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Composer\Package;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyBranchAliasValueFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(CopyBranchAliasValueFromRootToPackageOperation::class)]
final class CopyBranchAliasValueFromRootToPackageOperationTest extends TestCase
{
    private CopyBranchAliasValueFromRootToPackageOperation $worker;

    private JsonFileInterface&MockObject $rootFile;

    private JsonFileInterface&MockObject $packageFile;

    protected function setUp(): void
    {
        $this->rootFile = $this->createMock(JsonFileInterface::class);
        $this->worker = new CopyBranchAliasValueFromRootToPackageOperation($this->rootFile);
        $this->packageFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillUpdateBranchAliasValue(): void
    {
        $extra = [
            'branch-alias' => '1.2.x-dev',
        ];
        $this->rootFile->method('getSection')->willReturn($extra);

        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'extra',
                $this->callback(fn($value): true => $value === $extra)
            )
        ;

        $this->worker->apply($this->packageFile);
    }
}
