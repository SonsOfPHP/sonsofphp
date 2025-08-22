<?php

declare(strict_types=1);

namespace Chorale\Tests\Config;

use Chorale\Config\ConfigDefaults;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigDefaults::class)]
#[Group('unit')]
#[Small]
final class ConfigDefaultsTest extends TestCase
{
    public function testResolveFillsFallbacks(): void
    {
        $d = new ConfigDefaults();
        $out = $d->resolve([]);
        $this->assertSame('git@github.com', $out['repo_host']);
    }

    #[Test]
    public function testResolveMergesRules(): void
    {
        $d = new ConfigDefaults();
        $out = $d->resolve(['rules' => ['keep_history' => false]]);
        $this->assertFalse($out['rules']['keep_history']);
    }

    #[Test]
    public function testResolveComputesDefaultRepoTemplateWhenNotProvided(): void
    {
        $d = new ConfigDefaults();
        $out = $d->resolve(['repo_vendor' => 'Acme']);
        $this->assertSame('git@github.com:Acme/{name:kebab}.git', $out['default_repo_template']);
    }

    #[Test]
    public function testResolveKeepsExplicitDefaultRepoTemplate(): void
    {
        $d = new ConfigDefaults();
        $out = $d->resolve(['default_repo_template' => 'x:{y}/{z}']);
        $this->assertSame('x:{y}/{z}', $out['default_repo_template']);
    }

    #[Test]
    public function testResolveOverridesRequireFilesList(): void
    {
        $d = new ConfigDefaults();
        $out = $d->resolve(['rules' => ['require_files' => ['README.md']]]);
        $this->assertSame(['README.md'], $out['rules']['require_files']);
    }
}
