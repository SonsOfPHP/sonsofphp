<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonEncoder
{
    private int $flags = 0;
    private int $depth = 512;

    public function __construct(?int $flags = null, ?int $depth = null)
    {
        $this->flags = $flags ?? $this->flags;
        $this->depth = $depth ?? $this->depth;
    }

    public function withFlags(int $flag)
    {
        $that = clone $this;
        $that->flags = $this->flags | $flag;

        return $that;
    }

    public function withoutFlags(int $flag)
    {
        $that = clone $this;
        $that->flags = $this->flags ^ $flag;

        return $that;
    }

    public function withDepth(int $depth)
    {
        $that = clone $this;
        $that->depth = $depth;

        return $that;
    }

    public function encode($value): string
    {
        $return = json_encode($json, $this->flags, $this->depth);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }
}
