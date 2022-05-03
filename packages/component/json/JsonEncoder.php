<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * JSON Encoder will encode data to a json string
 *
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
        $that->flags = $this->flags & ~$flag;

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
        $return = json_encode($value, $this->flags, $this->depth);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }

    public function forceObject()
    {
        return $this->withFlags(JSON_FORCE_OBJECT);
    }

    public function hexQuot()
    {
        return $this->withFlags(JSON_HEX_QUOTE);
    }

    public function hexTag()
    {
        return $this->withFlags(JSON_HEX_TAG);
    }

    public function hexAmp()
    {
        return $this->withFlags(JSON_HEX_AMP);
    }

    public function hexApos()
    {
        return $this->withFlags(JSON_HEX_APOS);
    }

    public function invalidUtf8Ignore()
    {
        return $this->withFlags(JSON_INVALID_UTF8_IGNORE);
    }

    public function invalidUtf8Substitute()
    {
        return $this->withFlags(JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function numericCheck()
    {
        return $this->withFlags(JSON_NUMERIC_CHECK);
    }

    public function partialOutputOnError()
    {
        return $this->withFlags(JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    public function preserveZeroFraction()
    {
        return $this->withFlags(JSON_PRESERVE_ZERO_FRACTION);
    }

    public function prettyPrint()
    {
        return $this->withFlags(JSON_PRETTY_PRINT);
    }

    public function unescapedLineTerminators()
    {
        return $this->withFlags(JSON_UNESCAPED_LINE_TERMINATORS);
    }

    public function unescapedSlashes()
    {
        return $this->withFlags(JSON_UNESCAPED_SLASHES);
    }

    public function unescapedUnicode()
    {
        return $this->withFlags(JSON_UNESCAPED_UNICODE);
    }

    public function throwOnError()
    {
        return $this->withFlags(JSON_THROW_ON_ERROR);
    }
}
