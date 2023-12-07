<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageInterface
{
    public function setFrom($from): self;

    public function addFrom($from): self;

    public function setTo($to): self;

    public function addTo($to): self;

    public function setBody($body): self;
}
