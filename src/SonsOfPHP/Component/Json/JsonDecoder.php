<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * JSON Decoder will covert json to stdClass or array.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonDecoder extends AbstractEncoderDecoder
{
    public function __construct(bool $associative = null, int $depth = null, int $flags = null)
    {
        parent::__construct($flags, $depth);

        if (true === $associative) {
            $this->flags = $this->flags | \JSON_OBJECT_AS_ARRAY;
        }
    }

    public function decode(string $json)
    {
        $return = json_decode($json, null, $this->depth, $this->flags);

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }

    public function asArray()
    {
        return $this->withFlags(\JSON_OBJECT_AS_ARRAY);
    }

    /**
     * Decodes large integers as their original string value.
     */
    public function bigintAsString()
    {
        return $this->withFlags(\JSON_BIGINT_AS_STRING);
    }

    /**
     * Decodes JSON objects as PHP array.
     */
    public function objectAsArray()
    {
        return $this->withFlags(\JSON_OBJECT_AS_ARRAY);
    }
}
