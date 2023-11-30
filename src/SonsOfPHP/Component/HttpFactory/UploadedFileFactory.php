<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\UploadedFileFactoryInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UploadedFileFactory implements UploadedFileFactoryInterface
{
    use UploadedFileFactoryTrait;
}
