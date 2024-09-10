---
title: JSON - Overview
---

The JSON Component is a wrapper around php's `json_encode` and `json_decode`.

## Installation

``` shell
composer require sonsofphp/json
```

## Usage

### Json

``` php
<?php
use SonsOfPHP\Component\Json\Json;

// You use this as a drop-in replacement for json_encode and json_decode
$json = Json::encode($value);
$object = Json::decode($json);
$array = Json::decode($json, true);

// It comes with a JsonEncoder and JsonDecoder, see below for usage
$json = new Json();
$encoder = $json->getEncoder();
$decoder = $json->getDecoder();
```

### JsonEncoder

``` php
<?php
use SonsOfPHP\Component\Json\JsonEncoder;

$encoder = new JsonEncoder();

// You can add various flags using the various JSON constants PHP provides. You
// can enter them one at a time or combine them
$encoder = $encoder
    ->withFlags(JSON_HEX_QUOT)
    ->withFlags(JSON_HEX_TAG | JSON_HEX_AMP);

// Removing flags is just as easy
$encoder = $encoder->withoutFlags(JSON_HEX_TAG);

// Changing the depth is just as easy
$encoder = $encoder->withDepth(256);

// Once you are ready, you just encode the value to output json
$json = $encoder->encode($value);

// You can chain everything together as well
$json = (new JsonEncoder())
    ->withDepth(256)
    ->withFlags(JSON_HEX_QUOT)
    ->encode($value);
```

### JsonDecoder

``` php
<?php
use SonsOfPHP\Component\Json\JsonDecoder;

$decoder = new JsonDecoder();

// Add & Remove flags with the constants provided by PHP
$decoder = $decoder
    ->withFlags(...)
    ->withoutFlags(...);

// Change the depth
$decoder = $decoder->withDepth(256);

// Easy to return array
$decoder = $decoder->asArray();

// Decode the json
$array = $decoder->decode($json);
```

## Need Help?

Check out [Sons of PHP's Organization Discussions][discussions].

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
