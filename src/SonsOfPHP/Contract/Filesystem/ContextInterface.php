<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Filesystem;

use ArrayAccess;
use IteratorAggregate;

/**
 * Context is an object that may be passed into some of the methods. The purpose
 * of this is having the ability to use specific context based on the adapter
 * being used. This can also be used to provide options for some methods, for example,
 * we may want to overwrite a file if it exists.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface extends ArrayAccess, IteratorAggregate {}
