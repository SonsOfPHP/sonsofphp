<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageInterface
{
    public function getFrom(): ?string;
    public function setFrom($from): self;
    public function addFrom($from): self;

    public function getTo(): ?string;
    public function setTo($to): self;
    public function addTo($to): self;

    public function getSubject(): ?string;
    public function setSubject(string $subject): self;

    public function getBody(): ?string;
    public function setBody(string $body): self;
}
