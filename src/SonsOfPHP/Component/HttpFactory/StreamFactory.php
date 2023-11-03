<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\StreamFactoryInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class StreamFactory implements StreamFactoryInterface
{
    use StreamFactoryTrait;
}
