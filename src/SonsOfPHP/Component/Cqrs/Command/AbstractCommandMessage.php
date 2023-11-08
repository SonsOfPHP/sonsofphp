<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Contract\Cqrs\Command\CommandMessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCommandMessage extends AbstractMessage implements CommandMessageInterface {}
