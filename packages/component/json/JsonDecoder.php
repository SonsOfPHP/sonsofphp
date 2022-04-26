<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonDecoder
{
    private int $depth = 512;
    private int $flags = 0;

    public function __construct(?bool $associative = null, ?int $depth = null, ?int $flags = null)
    {
        $this->depth = $depth ?? $this->depth;
        $this->flags = $flags ?? $this->flags;

        if (true === $associative) {
            $this->flags = $this->flags | JSON_OBJECT_AS_ARRAY;
        }
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

    public function asArray()
    {
        return $this->withFlag(JSON_OBJECT_AS_ARRAY);
    }

    public function decode(string $json)
    {
        $return = json_decode($json, null, $this->depth, $this->flags);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }
}
