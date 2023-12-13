<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Validator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ConstraintViolationInterface
{
    public function getMessage(): string;
    public function getMessageTemplate(): string;
}
