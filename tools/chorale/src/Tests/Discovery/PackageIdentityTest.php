<?php

declare(strict_types=1);

namespace Chorale\Tests\Discovery;

use Chorale\Discovery\PackageIdentity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PackageIdentity::class)]
#[Group('unit')]
#[Small]
final class PackageIdentityTest extends TestCase
{
    public function testIdentityPrefersRepoUrlAndNormalizes(): void
    {
        $pi = new PackageIdentity();
        $id = $pi->identityFor('unused', 'SSH://GitHub.com/SonsOfPHP/Cookie.git');
        $this->assertSame('github.com/sonsofphp/cookie.git', $id);
    }

    #[Test]
    public function testIdentityFallsBackToLeaf(): void
    {
        $pi = new PackageIdentity();
        $id = $pi->identityFor('src/SonsOfPHP/Cookie');
        $this->assertSame('cookie', $id);
    }
}
