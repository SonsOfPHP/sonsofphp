<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Contract\Cqrs\Query\QueryMessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractQueryMessage extends AbstractMessage implements QueryMessageInterface {}
