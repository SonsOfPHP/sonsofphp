<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonWriter
{
    private JsonEncoder $encoder;

    public function __construct(JsonEncoder $encoder = null)
    {
        $this->encoder = $encoder ?? new JsonEncoder();
    }

    public function write(string $filename, $value, int $depth = null, int $flags = null)
    {
        $encoder = $this->encoder;

        if (null !== $depth) {
            $encoder = $encoder->withDepth($depth);
        }

        if (null !== $flags) {
            $encoder = $encoder->withFlags($flags);
        }

        return file_put_contents($filename, $encoder->encode($value));
    }
}
