<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpClient\Exception\RequestException;
use SonsOfPHP\Component\HttpMessage\Response;
use SonsOfPHP\Component\HttpMessage\Stream;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CurlHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        $stream   = new Stream();
        $response = (new Response())->withBody($stream);

        if (false === $ch = curl_init((string) $request->getUri())) {
            throw new RequestException('', $request);
        }
        curl_setopt_array($ch, [
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $contents = curl_exec($ch);
        curl_close($ch);

        $stream->write($contents);
        $stream->rewind();

        return $response;
    }
}
