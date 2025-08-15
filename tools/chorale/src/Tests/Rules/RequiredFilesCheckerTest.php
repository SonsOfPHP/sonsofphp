<?php

declare(strict_types=1);

namespace Chorale\Tests\Rules;

use Chorale\Rules\RequiredFilesChecker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequiredFilesChecker::class)]
#[Group('unit')]
#[Small]
final class RequiredFilesCheckerTest extends TestCase
{
    private function makePackage(bool $withFiles = true): array
    {
        $root = sys_get_temp_dir() . '/chorale_proj_' . uniqid();
        $pkg = $root . '/src/Acme/Lib';
        @mkdir($pkg, 0o777, true);
        if ($withFiles) {
            file_put_contents($pkg . '/composer.json', '{}');
            file_put_contents($pkg . '/LICENSE', '');
        }
        return [$root, 'src/Acme/Lib'];
    }

    #[Test]
    public function testMissingReturnsEmptyWhenAllPresent(): void
    {
        [$root, $pkg] = $this->makePackage(true);
        $c = new RequiredFilesChecker();
        $miss = $c->missing($root, $pkg, ['composer.json','LICENSE']);
        self::assertSame([], $miss);
    }

    #[Test]
    public function testMissingReturnsListOfMissing(): void
    {
        [$root, $pkg] = $this->makePackage(false);
        $c = new RequiredFilesChecker();
        $miss = $c->missing($root, $pkg, ['composer.json','LICENSE']);
        self::assertSame(['composer.json','LICENSE'], $miss);
    }
}
