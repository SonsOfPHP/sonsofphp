<?php

declare(strict_types=1);

namespace Chorale\Tests\Repo;

use Chorale\Repo\TemplateRenderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TemplateRenderer::class)]
#[Group('unit')]
#[Small]
final class TemplateRendererTest extends TestCase
{
    public function testValidateDetectsUnknownPlaceholder(): void
    {
        $r = new TemplateRenderer();
        $issues = $r->validate('x/{unknown}');
        self::assertContains("Unknown placeholder 'unknown'", $issues);
    }

    #[Test]
    public function testValidateDetectsUnknownFilter(): void
    {
        $r = new TemplateRenderer();
        $issues = $r->validate('x/{name:oops}');
        self::assertContains("Unknown filter 'oops' for 'name'", $issues);
    }

    #[Test]
    public function testRenderAppliesLowerFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:lower}', ['name' => 'Cookie']);
        self::assertSame('cookie', $out);
    }

    #[Test]
    public function testRenderAppliesUpperFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:upper}', ['name' => 'Cookie']);
        self::assertSame('COOKIE', $out);
    }

    #[Test]
    public function testRenderKebabFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:kebab}', ['name' => 'My Cookie_Package']);
        self::assertSame('my-cookie-package', $out);
    }

    #[Test]
    public function testRenderSnakeFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:snake}', ['name' => 'My Cookie-Package']);
        self::assertSame('my_cookie_package', $out);
    }

    #[Test]
    public function testRenderCamelFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:camel}', ['name' => 'my-cookie package']);
        self::assertSame('myCookiePackage', $out);
    }

    #[Test]
    public function testRenderPascalFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:pascal}', ['name' => 'my-cookie package']);
        self::assertSame('MyCookiePackage', $out);
    }

    #[Test]
    public function testRenderDotFilter(): void
    {
        $r = new TemplateRenderer();
        $out = $r->render('{name:dot}', ['name' => 'my cookie-package']);
        self::assertSame('my.cookie.package', $out);
    }
}
