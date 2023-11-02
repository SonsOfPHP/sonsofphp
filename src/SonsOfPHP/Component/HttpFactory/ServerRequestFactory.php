<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\ServerRequestFactoryInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    use ServerRequestFactoryTrait;
}
