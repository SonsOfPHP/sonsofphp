<?php

declare(strict_types=1);

namespace Chorale\Tests\Config;

use Chorale\Config\ConfigDefaultsInterface;
use Chorale\Config\ConfigNormalizer;
use Chorale\Util\SortingInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigNormalizer::class)]
#[Group('unit')]
#[Small]
final class ConfigNormalizerTest extends TestCase
{
    /** @var SortingInterface&MockObject */
    private SortingInterface $sorting;

    /** @var ConfigDefaultsInterface&MockObject */
    private ConfigDefaultsInterface $defaults;

    protected function setUp(): void
    {
        $this->sorting = $this->createMock(SortingInterface::class);
        $this->defaults = $this->createMock(ConfigDefaultsInterface::class);
        $this->defaults->method('resolve')->willReturn([
            'repo_host' => 'git@github.com',
            'repo_vendor' => 'SonsOfPHP',
            'repo_name_template' => '{name:kebab}.git',
            'default_repo_template' => 'git@github.com:{repo_vendor}/{repo_name_template}',
            'default_branch' => 'main',
            'splitter' => 'splitsh',
            'tag_strategy' => 'inherit-monorepo-tag',
            'rules' => [
                'keep_history' => true,
                'skip_if_unchanged' => true,
                'require_files' => ['composer.json','LICENSE'],
            ],
        ]);
        $this->sorting->method('sortPatterns')->willReturnCallback(fn(array $a): array => $a);
        $this->sorting->method('sortTargets')->willReturnCallback(fn(array $a): array => $a);
    }

    public function testRedundantPatternOverrideIsRemoved(): void
    {
        $n = new ConfigNormalizer($this->sorting, $this->defaults);
        $out = $n->normalize(['patterns' => [['match' => 'src/*', 'repo_host' => 'git@github.com']]]);
        $this->assertArrayNotHasKey('repo_host', $out['patterns'][0]);
    }

    #[Test]
    public function testRedundantTargetOverrideIsRemoved(): void
    {
        $n = new ConfigNormalizer($this->sorting, $this->defaults);
        $out = $n->normalize(['targets' => [['path' => 'a/b', 'repo_vendor' => 'SonsOfPHP']]]);
        $this->assertArrayNotHasKey('repo_vendor', $out['targets'][0]);
    }

    #[Test]
    public function testTopLevelDefaultsCopied(): void
    {
        $n = new ConfigNormalizer($this->sorting, $this->defaults);
        $out = $n->normalize([]);
        $this->assertSame('git@github.com', $out['repo_host']);
    }
}
