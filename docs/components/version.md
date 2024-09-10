---
title: Version
---

# Version Component

The Version component allows you an easy way to parse and compare versions. It
supports both Semversion and package manager syntax.

## Installation

```shell
composer require sonsofphp/version
```

## Usage

The base `Version` supports semver standards.

```php
<?php
use SonsOfPHP\Component\Version\Version;

// You can create versions two different ways
$version = new Version('1.2.3');
$version = Version::from('1.2.3');

echo $version; // prints "1.2.3"
$versionString = $version->toString(); // $versionString === "1.2.3"

// Easy API
$major = $version->getMajor(); // $major === 1
$minor = $version->getMinor(); // $minor === 2
$patch = $version->getPatch(); // $patch === 3

// Comparing Versions is easy too
$currentVersion = new Version('1.1.1');
$latestVersion  = new Version('1.1.2');

// if $latestVersion > $currentVersion
if ($latestVersion->isGreaterThan($currentVersion)) {
    // run upgrade
}
// Also supports "isLessThan" and "isEqualTo"
```

### Advanced Usage

You can also use Pre-release and Build Metadata with your versions.

```php
<?php
use SonsOfPHP\Component\Version\Version;

// Create a new Version that includes Pre-release and/or Build Metadata
$version = new Version('1.2.3-RC1+buildMetaData');

// You can grab the info as well
$preRelease = $version->getPreRelease(); // $preRelease === "RC1"
$build = $version->getBuild(); // $build === "RC1"
```
