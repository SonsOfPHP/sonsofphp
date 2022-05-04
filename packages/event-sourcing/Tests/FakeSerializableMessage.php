<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Message\AbstractGenericMessage;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;

class FakeSerializableMessage extends AbstractGenericMessage implements SerializableMessageInterface
{
}
