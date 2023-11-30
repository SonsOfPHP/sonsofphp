<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search;

use SonsOfPHP\Contract\Search\QueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Query implements QueryInterface, \ArrayAccess, \JsonSerializable
{
}
