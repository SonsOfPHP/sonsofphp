<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json;

/**
 * Json Encoder/Decoder.
 *
 * Usage:
 *   // Make it simple to drop in as replacement
 *   $json   = Json::encode($value);
 *   $object = Json::decode($json);
 *   $array  = Json::decode($json, true);
 *
 *   $jsonObj = new Json();
 *   $json = $jsonObj->getEncoder()
 *       ->withDepth(512)
 *       ->withFlags(JSON_PRETTY_PRINT)
 *       ->encode($value);
 *
 *   $object = $jsonObj->getDecoder()
 *       ->withDepth(512)
 *       ->withFlags(JSON_BIGINT_AS_STRING)
 *       ->decode($json);
 *
 *   $array = $jsonObj->getDecoder()
 *       ->withDepth(512)
 *       ->withFlags(JSON_BIGINT_AS_STRING)
 *       ->asArray() // same as ->withFlags(JSON_OBJECT_AS_ARRAY)
 *       ->decode($json);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Json
{
    private readonly JsonDecoder $decoder;

    private readonly JsonEncoder $encoder;

    public function __construct(JsonEncoder $encoder = null, JsonDecoder $decoder = null)
    {
        $this->encoder = $encoder ?? new JsonEncoder();
        $this->decoder = $decoder ?? new JsonDecoder();
    }

    public function getEncoder(): JsonEncoder
    {
        return $this->encoder;
    }

    public function getDecoder(): JsonDecoder
    {
        return $this->decoder;
    }

    public static function encode($value, int $flags = null, int $depth = null): string
    {
        return (new JsonEncoder($flags, $depth))->encode($value);
    }

    public static function decode(string $json, bool $associative = null, int $depth = null, int $flags = null)
    {
        return (new JsonDecoder($associative, $depth, $flags))->decode($json);
    }
}
