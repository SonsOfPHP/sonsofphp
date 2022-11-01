<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * This class is used to reduce some of the code duplication that appears
 * in the JsonEncoder and JsonDecoder classes.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractEncoderDecoder
{
    protected int $flags = 0;
    protected int $depth = 512;

    public function __construct(int $flags = null, int $depth = null)
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

    /**
     * Ignore invalid UTF-8 characters. Available as of PHP 7.2.0.
     */
    public function invalidUtf8Ignore()
    {
        return $this->withFlags(\JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Convert invalid UTF-8 characters to \0xfffd (Unicode Character
     * 'REPLACEMENT CHARACTER') Available as of PHP 7.2.0.
     */
    public function invalidUtf8Substitute()
    {
        return $this->withFlags(\JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * Throws JsonException if an error occurs instead of setting the global
     * error state that is retrieved with json_last_error() and
     * json_last_error_msg(). JSON_PARTIAL_OUTPUT_ON_ERROR takes precedence
     * over JSON_THROW_ON_ERROR. Available as of PHP 7.3.0.
     */
    public function throwOnError()
    {
        return $this->withFlags(\JSON_THROW_ON_ERROR);
    }
}
