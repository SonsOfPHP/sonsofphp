<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * JSON Encoder will encode data to a json string.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonEncoder extends AbstractEncoderDecoder
{
    public function encode($value): string
    {
        $return = json_encode($value, $this->flags, $this->depth);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }

    /**
     * Outputs an object rather than an array when a non-associative array is
     * used. Especially useful when the recipient of the output is expecting an
     * object and the array is empty.
     */
    public function forceObject()
    {
        return $this->withFlags(JSON_FORCE_OBJECT);
    }

    /**
     * All " are converted to \u0022.
     */
    public function hexQuot()
    {
        return $this->withFlags(JSON_HEX_QUOT);
    }

    /**
     * All < and > are converted to \u003C and \u003E.
     */
    public function hexTag()
    {
        return $this->withFlags(JSON_HEX_TAG);
    }

    /**
     * All & are converted to \u0026.
     */
    public function hexAmp()
    {
        return $this->withFlags(JSON_HEX_AMP);
    }

    /**
     * All ' are converted to \u0027.
     */
    public function hexApos()
    {
        return $this->withFlags(JSON_HEX_APOS);
    }

    /**
     * Encodes numeric strings as numbers.
     */
    public function numericCheck()
    {
        return $this->withFlags(JSON_NUMERIC_CHECK);
    }

    /**
     * Substitute some unencodable values instead of failing.
     */
    public function partialOutputOnError()
    {
        return $this->withFlags(JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * Ensures that float values are always encoded as a float value.
     */
    public function preserveZeroFraction()
    {
        return $this->withFlags(JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * Use whitespace in returned data to format it.
     */
    public function prettyPrint()
    {
        return $this->withFlags(JSON_PRETTY_PRINT);
    }

    /**
     * The line terminators are kept unescaped when JSON_UNESCAPED_UNICODE is
     * supplied. It uses the same behaviour as it was before PHP 7.1 without
     * this constant. Available as of PHP 7.1.0.
     */
    public function unescapedLineTerminators()
    {
        return $this->withFlags(JSON_UNESCAPED_LINE_TERMINATORS);
    }

    /**
     * Don't escape /.
     */
    public function unescapedSlashes()
    {
        return $this->withFlags(JSON_UNESCAPED_SLASHES);
    }

    /**
     * Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
     */
    public function unescapedUnicode()
    {
        return $this->withFlags(JSON_UNESCAPED_UNICODE);
    }
}
