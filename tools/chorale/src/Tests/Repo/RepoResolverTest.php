<?php

declare(strict_types=1);

namespace Chorale\Tests\Repo;

use Chorale\Repo\RepoResolver;
use Chorale\Repo\TemplateRenderer;
use Chorale\Util\PathUtils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RepoResolver::class)]
#[Group('unit')]
#[Small]
final class RepoResolverTest extends TestCase
{
    private array $defaults = [
        'repo_host' => 'git@github.com',
        'repo_vendor' => 'Acme',
        'repo_name_template' => '{name:kebab}.git',
        'default_repo_template' => '{repo_host}:{repo_vendor}/{name:kebab}.git',
    ];

    public function testResolveUsesTargetRepoWhenPresent(): void
    {
        $r = new RepoResolver(new TemplateRenderer(), new PathUtils());
        $url = $r->resolve($this->defaults, [], ['repo' => 'git@gh:x/{name}'], 'src/Acme/Foo', 'Foo');
        $this->assertSame('git@gh:x/Foo', $url);
    }

    #[Test]
    public function testResolveUsesPatternRepoWhenTargetMissing(): void
    {
        $r = new RepoResolver(new TemplateRenderer(), new PathUtils());
        $url = $r->resolve($this->defaults, ['repo' => '{repo_host}:{repo_vendor}/{name:snake}'], [], 'src/Acme/Foo', 'FooBar');
        $this->assertSame('git@github.com:Acme/foo_bar', $url);
    }

    #[Test]
    public function testResolveUsesDefaultTemplateOtherwise(): void
    {
        $r = new RepoResolver(new TemplateRenderer(), new PathUtils());
        $url = $r->resolve($this->defaults, [], [], 'src/Acme/Cookie', 'Cookie');
        $this->assertSame('git@github.com:Acme/cookie.git', $url);
    }

    #[Test]
    public function testResolveDerivesNameFromLeafWhenNameNull(): void
    {
        $r = new RepoResolver(new TemplateRenderer(), new PathUtils());
        $url = $r->resolve($this->defaults, [], [], 'src/Acme/CamelCase', null);
        $this->assertSame('git@github.com:Acme/camel-case.git', $url);
    }
}
