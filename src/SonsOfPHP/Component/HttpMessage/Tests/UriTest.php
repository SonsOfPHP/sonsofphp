<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpMessage\Uri;

/**
 * @uses \SonsOfPHP\Component\HttpMessage\Uri
 * @coversNothing
 */
#[CoversClass(Uri::class)]
final class UriTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UriInterface::class, new Uri());
    }

    public function testWithQueryParam(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withQueryParam('page', 1));
        $this->assertSame('page=1', $uri->withQueryParam('page', 1)->getQuery());
    }

    public function testWithQueryParamsCanBeUsedToRemove(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com?page=1&limit=100');
        $this->assertSame('', $uri->withQueryParams(null)->getQuery());
    }

    public function testWithQueryParamsWillAdd(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('page=1&limit=100', $uri->withQueryParams(['page' => 1])->withQueryParams(['limit' => 100])->getQuery());
    }

    public function testWithQueryParams(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withQueryParams(['page' => 1]));
        $this->assertSame('page=1', $uri->withQueryParams(['page' => 1])->getQuery());
    }

    public function testGetQueryWorksAsExpectedWhenComplexQueryParams(): void
    {
        $uri = new Uri();
        $this->assertSame(
            'search=search%20term&filters[active]=1',
            $uri->withQueryParams([
                'search' => 'search term',
                'filters' => [
                    'active' => '1',
                ],
            ])->getQuery()
        );
    }

    public function testGetQueryWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('', $uri->getQuery());

        $uri = new Uri('https://docs.sonsofphp.com?q');
        $this->assertSame('q', $uri->getQuery());

        $uri = new Uri('https://docs.sonsofphp.com?q=test%20query');
        $this->assertSame('q=test%20query', $uri->getQuery());

        $uri = new Uri('https://docs.sonsofphp.com?page=1&limit=100');
        $this->assertSame('page=1&limit=100', $uri->getQuery());
    }

    public function testConstructWithQuery(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com?q=test');
        $this->assertSame('q=test', $uri->getQuery());
    }

    public function testItCanBeCreatedWithBasicUri(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('docs.sonsofphp.com', $uri->getHost());
    }

    public function testGetSchemeWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('https', $uri->getScheme());
    }

    public function testGetHostWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('docs.sonsofphp.com', $uri->getHost());
    }

    public function testGetAuthorityWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('docs.sonsofphp.com', $uri->getAuthority());

        $uri = new Uri('https://user:pass@docs.sonsofphp.com');
        $this->assertSame('user:pass@docs.sonsofphp.com', $uri->getAuthority());

        $uri = new Uri('https://user:pass@docs.sonsofphp.com:2131');
        $this->assertSame('user:pass@docs.sonsofphp.com:2131', $uri->getAuthority());
    }

    public function testGetUserInfoWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('', $uri->getUserInfo());

        $uri = new Uri('https://user@docs.sonsofphp.com');
        $this->assertSame('user', $uri->getUserInfo());

        $uri = new Uri('https://user:password@docs.sonsofphp.com');
        $this->assertSame('user:password', $uri->getUserInfo());
    }

    public function testGetPortWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame(443, $uri->getPort());

        $uri = new Uri('https://docs.sonsofphp.com:2131');
        $this->assertSame(2131, $uri->getPort());
    }

    public function testGetPathWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('', $uri->getPath());

        $uri = new Uri('https://docs.sonsofphp.com/');
        $this->assertSame('/', $uri->getPath());

        $uri = new Uri('https://docs.sonsofphp.com/components');
        $this->assertSame('/components', $uri->getPath());
    }

    public function testGetFragmentWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('', $uri->getFragment());

        $uri = new Uri('https://docs.sonsofphp.com#frag');
        $this->assertSame('frag', $uri->getFragment());
    }

    public function testStringableWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertSame('https://docs.sonsofphp.com', (string) $uri);

        $uri = new Uri('https://user@docs.sonsofphp.com');
        $this->assertSame('https://user@docs.sonsofphp.com', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com');
        $this->assertSame('https://user:password@docs.sonsofphp.com', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com:2131');
        $this->assertSame('https://user:password@docs.sonsofphp.com:2131', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com:2131/components');
        $this->assertSame('https://user:password@docs.sonsofphp.com:2131/components', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com:2131/components');
        $this->assertSame('https://user:password@docs.sonsofphp.com:2131/components', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com:2131/components?page=1');
        $this->assertSame('https://user:password@docs.sonsofphp.com:2131/components?page=1', (string) $uri);

        $uri = new Uri('https://user:password@docs.sonsofphp.com:2131/components?page=1#results');
        $this->assertSame('https://user:password@docs.sonsofphp.com:2131/components?page=1#results', (string) $uri);
    }

    public function testWithSchemeWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withScheme('http'));
        $this->assertSame($uri, $uri->withScheme('https'));
    }

    public function testWithUserInfoWorksAsExpected(): void
    {
        $uri = new Uri('https://user:password@docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withUserInfo('user'));
        $this->assertSame($uri, $uri->withUserInfo('user', 'password'));
    }

    public function testWithHostWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withHost('sonsofphp.com'));
        $this->assertSame($uri, $uri->withHost('docs.sonsofphp.com'));
    }

    public function testWithPortWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withPort(2131));
        $this->assertSame($uri, $uri->withPort(443));
    }

    public function testWithPathWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withPath('/test'));
        $this->assertSame($uri, $uri->withPath(''));
    }

    public function testWithQueryWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withQuery('test=yes'));
        $this->assertSame($uri, $uri->withQuery(''));

        $this->assertSame('testing=yes', $uri->withQuery('testing=yes')->getQuery());
    }

    public function testWithFragmentWorksAsExpected(): void
    {
        $uri = new Uri('https://docs.sonsofphp.com');
        $this->assertNotSame($uri, $uri->withFragment('test'));
        $this->assertSame($uri, $uri->withFragment(''));

        $uri = new Uri('https://docs.sonsofphp.com#title');
        $this->assertNotSame($uri, $uri->withFragment('test'));
        $this->assertSame($uri, $uri->withFragment('#title'));
        $this->assertSame($uri, $uri->withFragment('title'));
    }
}
