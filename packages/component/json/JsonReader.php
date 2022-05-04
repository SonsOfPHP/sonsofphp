<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * Reads json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class JsonReader
{
    private JsonDecoder $decoder;

    public function __construct(?JsonDecoder $decoder = null)
    {
        $this->decoder = $decoder ?? new JsonDecoder();
    }

    /**
     */
    public function read(string $filename, ?bool $associative = null, ?int $depth = null, ?int $flags = null): array
    {
        if (!is_readable($filename)) {
            throw new JsonException(sprintf('The file "%s" does not exist or cannot be read', $filename));
        }

        $decoder = $this->decoder;

        if (null !== $associative) {
            $decoder = $decoder->objectAsArray();
        }

        if (null !== $depth) {
            $decoder = $decoder->withDepth($depth);
        }

        if (null !== $flags) {
            $decoder = $decoder->withFlags($flags);
        }

        return $decoder->decode(file_get_contents($filename));
    }
}
