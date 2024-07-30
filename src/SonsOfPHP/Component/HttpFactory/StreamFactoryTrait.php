<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpMessage\Stream;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
trait StreamFactoryTrait
{
    /**
     * {@inheritdoc}
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $content);
        fseek($resource, 0);

        return new Stream($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        // #todo Error Handling, filename might be invalid or mode may be invalid

        return new Stream(fopen($filename, $mode));
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('resource is invalid');
        }

        return new Stream($resource);
    }
}
