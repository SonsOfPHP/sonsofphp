<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;

class FakeSerializableMessage extends AbstractSerializableMessage implements SerializableMessageInterface {}
