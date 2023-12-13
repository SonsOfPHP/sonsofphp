<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Validator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ConstraintDescriptorInterface
{
    public function getMessageTemplate(): string;
}
