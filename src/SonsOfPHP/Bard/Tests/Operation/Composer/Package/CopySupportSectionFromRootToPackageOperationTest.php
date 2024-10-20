<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Composer\Package;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Composer\Package\CopySupportSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(CopySupportSectionFromRootToPackageOperation::class)]
final class CopySupportSectionFromRootToPackageOperationTest extends TestCase
{
    private CopySupportSectionFromRootToPackageOperation $worker;

    private JsonFileInterface&MockObject $rootFile;

    private JsonFileInterface&MockObject $packageFile;

    protected function setUp(): void
    {
        $this->rootFile = $this->createMock(JsonFileInterface::class);
        $this->worker = new CopySupportSectionFromRootToPackageOperation($this->rootFile);
        $this->packageFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillCopySupportSection(): void
    {
        $support = [
            'issues' => 'https://sonsofphp.com/issues',
            'forum'  => 'https://sonsofphp.com/forum',
            'docs'   => 'https://sonsofphp.com/docs',
        ];
        $this->rootFile->method('getSection')->willReturn($support);

        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'support',
                $this->callback(fn($value): true => $value === $support)
            )
        ;

        $this->worker->apply($this->packageFile);
    }

    public function testItWillCopySupportSectionAndNotModifyPackageSupportDocs(): void
    {
        $root = [
            'issues' => 'https://sonsofphp.com/issues',
            'forum'  => 'https://sonsofphp.com/forum',
            'docs'   => 'https://sonsofphp.com/docs',
        ];
        $this->rootFile->method('getSection')->willReturn($root);

        $package = [
            'docs' => 'https://docs.sonsofphp.com',
        ];

        $expected = [
            'issues' => 'https://sonsofphp.com/issues',
            'forum'  => 'https://sonsofphp.com/forum',
            'docs'   => 'https://docs.sonsofphp.com',
        ];

        $this->packageFile->method('getSection')->willReturn($package);
        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'support',
                $this->callback(fn($value): true => $value === $expected)
            )
        ;

        $this->worker->apply($this->packageFile);
    }
}
