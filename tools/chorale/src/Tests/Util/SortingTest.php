<?php

declare(strict_types=1);

namespace Chorale\Tests\Util;

use Chorale\Util\Sorting;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Sorting::class)]
#[Group('unit')]
#[Small]
final class SortingTest extends TestCase
{
    public function testSortPatternsPrefersLongerMatchFirst(): void
    {
        $s = new Sorting();
        $in = [
            ['match' => 'a/b'],
            ['match' => 'a/b/c'],
        ];
        $out = $s->sortPatterns($in);
        $this->assertSame('a/b/c', $out[0]['match']);
    }

    #[Test]
    public function testSortPatternsTiesAlphabetically(): void
    {
        $s = new Sorting();
        $in = [
            ['match' => 'b/b'],
            ['match' => 'a/b'],
        ];
        $out = $s->sortPatterns($in);
        $this->assertSame('a/b', $out[0]['match']);
    }

    #[Test]
    public function testSortTargetsPrimaryByPath(): void
    {
        $s = new Sorting();
        $in = [
            ['path' => 'b', 'name' => 'x'],
            ['path' => 'a', 'name' => 'z'],
        ];
        $out = $s->sortTargets($in);
        $this->assertSame('a', $out[0]['path']);
    }

    #[Test]
    public function testSortTargetsSecondaryByNameWhenSamePath(): void
    {
        $s = new Sorting();
        $in = [
            ['path' => 'a', 'name' => 'z'],
            ['path' => 'a', 'name' => 'a'],
        ];
        $out = $s->sortTargets($in);
        $this->assertSame('a', $out[0]['name']);
    }
}
