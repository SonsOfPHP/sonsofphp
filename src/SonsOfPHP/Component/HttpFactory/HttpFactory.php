<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\RequestFactoryInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class HttpFactory implements RequestFactoryInterface
{
    use RequestFactoryTrait;
    use ResponseFactoryTrait;
    use ServerRequestFactoryTrait;
    use StreamFactoryTrait;
    use UploadedFileFactoryTrait;
    use UriFactoryTrait;
}
