<?php

declare(strict_types=1);

namespace Chorale\Tests\Diff;

use Chorale\Config\ConfigDefaultsInterface;
use Chorale\Diff\ConfigDiffer;
use Chorale\Discovery\PackageIdentityInterface;
use Chorale\Discovery\PatternMatcherInterface;
use Chorale\Repo\RepoResolverInterface;
use Chorale\Rules\ConflictDetectorInterface;
use Chorale\Rules\RequiredFilesCheckerInterface;
use Chorale\Util\PathUtilsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigDiffer::class)]
#[Group('unit')]
#[Small]
final class ConfigDifferTest extends TestCase
{
    private function defaults(): array
    {
        return [
            'repo_host' => 'git@github.com',
            'repo_vendor' => 'Acme',
            'repo_name_template' => '{name:kebab}.git',
            'default_repo_template' => 'git@github.com:{repo_vendor}/{name:kebab}.git',
            'default_branch' => 'main',
            'splitter' => 'splitsh',
            'tag_strategy' => 'inherit-monorepo-tag',
            'rules' => [
                'keep_history' => true,
                'skip_if_unchanged' => true,
                'require_files' => ['composer.json','LICENSE'],
            ],
        ];
    }

    private function stubPaths(): PathUtilsInterface
    {
        return new class implements PathUtilsInterface {
            public function normalize(string $path): string
            {
                return $path;
            }

            public function isUnder(string $path, string $root): bool
            {
                return false;
            }

            public function match(string $pattern, string $path): bool
            {
                return false;
            }

            public function leaf(string $path): string
            {
                $pos = strrpos($path, '/');
                return $pos === false ? $path : substr($path, $pos + 1);
            }
        };
    }

    private function newDiffer(
        ConfigDefaultsInterface $defaults,
        PatternMatcherInterface $matcher,
        RepoResolverInterface $resolver,
        PackageIdentityInterface $identity,
        RequiredFilesCheckerInterface $required
    ): ConfigDiffer {
        return new ConfigDiffer($defaults, $matcher, $resolver, $identity, $required, $this->stubPaths());
    }

    #[Test]
    public function testClassifiesNewWhenNotInConfig(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        $resolver->method('resolve')->willReturn('git@github.com:Acme/foo.git');

        $identity = $this->createMock(PackageIdentityInterface::class);
        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn([]);
        $this->createMock(ConflictDetectorInterface::class);

        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);

        $out = $differ->diff(['targets' => [], 'patterns' => []], ['src/Acme/Foo'], []);
        $this->assertSame('src/Acme/Foo', $out['new'][0]['path']);
    }

    #[Test]
    public function testDetectsRenameWhenIdentityMatches(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        // Called for new path and old path
        $resolver->method('resolve')->willReturnOnConsecutiveCalls(
            'git@github.com:Acme/new.git',
            'git@github.com:Acme/old.git',
            'git@github.com:Acme/old.git'
        );

        $identity = $this->createMock(PackageIdentityInterface::class);
        $identity->method('identityFor')->willReturn('same-id');

        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn([]);
        $this->createMock(ConflictDetectorInterface::class);

        $config = [
            'targets' => [
                ['path' => 'src/Acme/Old'],
            ],
            'patterns' => [],
        ];
        $discovered = ['src/Acme/New'];

        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);
        $out = $differ->diff($config, $discovered, []);

        $this->assertSame('src/Acme/Old', $out['renamed'][0]['from']);
    }

    #[Test]
    public function testReportsIssuesWhenRequiredFilesMissing(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        $resolver->method('resolve')->willReturn('git@github.com:Acme/foo.git');

        $identity = $this->createMock(PackageIdentityInterface::class);
        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn(['composer.json']);
        $this->createMock(ConflictDetectorInterface::class);

        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);

        $out = $differ->diff(['targets' => [['path' => 'src/Acme/Foo']], 'patterns' => []], ['src/Acme/Foo'], []);
        $this->assertSame(['composer.json'], $out['issues'][0]['missing']);
    }

    #[Test]
    public function testReportsDriftWhenRedundantOverridePresent(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        $resolver->method('resolve')->willReturn('git@github.com:Acme/foo.git');

        $identity = $this->createMock(PackageIdentityInterface::class);
        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn([]);
        $this->createMock(ConflictDetectorInterface::class);

        $config = [
            'targets' => [
                ['path' => 'src/Acme/Foo', 'repo_vendor' => 'Acme'],
            ],
            'patterns' => [],
        ];
        $discovered = ['src/Acme/Foo'];

        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);
        $out = $differ->diff($config, $discovered, []);

        $this->assertSame('src/Acme/Foo', $out['drift'][0]['path']);
    }

    #[Test]
    public function testReportsConflictsWhenMultiplePatternsMatch(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([0, 1]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        $resolver->method('resolve')->willReturn('git@github.com:Acme/foo.git');

        $identity = $this->createMock(PackageIdentityInterface::class);
        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn([]);
        $this->createMock(ConflictDetectorInterface::class);

        $config = [
            'targets' => [['path' => 'src/Acme/Foo']],
            'patterns' => [['match' => 'src/*'], ['match' => 'src/Acme/*']],
        ];
        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);
        $out = $differ->diff($config, ['src/Acme/Foo'], []);
        $this->assertSame([0, 1], $out['conflicts'][0]['patterns']);
    }

    #[Test]
    public function testOkGroupWhenNoDriftOrIssues(): void
    {
        $defaults = $this->createMock(ConfigDefaultsInterface::class);
        $defaults->method('resolve')->willReturn($this->defaults());

        $matcher = $this->createMock(PatternMatcherInterface::class);
        $matcher->method('allMatches')->willReturn([]);

        $resolver = $this->createMock(RepoResolverInterface::class);
        $resolver->method('resolve')->willReturn('git@github.com:Acme/foo.git');

        $identity = $this->createMock(PackageIdentityInterface::class);
        $required = $this->createMock(RequiredFilesCheckerInterface::class);
        $required->method('missing')->willReturn([]);
        $this->createMock(ConflictDetectorInterface::class);

        $config = [
            'targets' => [['path' => 'src/Acme/Foo']],
            'patterns' => [],
        ];
        $differ = $this->newDiffer($defaults, $matcher, $resolver, $identity, $required);
        $out = $differ->diff($config, ['src/Acme/Foo'], []);
        $this->assertSame('src/Acme/Foo', $out['ok'][0]['path']);
    }
}
