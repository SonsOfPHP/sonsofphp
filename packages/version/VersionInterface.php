<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version;

use SonsOfPHP\Component\Version\Exception\VersionException;

/**
 * Version Interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface VersionInterface
{
    /**
     * $version = Version::from('1.2.3');.
     *
     * @throws VersionException
     *
     * @return static
     */
    public static function from(string $version): VersionInterface;

    /**
     * Returns the version.
     */
    public function toString(): string;

    /**
     * Returns the Major Version.
     */
    public function getMajor(): int;

    /**
     * Returns the Minor Version.
     */
    public function getMinor(): int;

    /**
     * Returns the Patch Version.
     */
    public function getPatch(): int;

    /**
     * Returns the Pre-release (if any).
     *
     * @return string
     */
    public function getPreRelease(): ?string;

    /**
     * Returns the Build Metadata (if any).
     *
     * @return string
     */
    public function getBuild(): ?string;

    /**
     * Returns
     * -1 = $this  <  $version
     *  0 = $this === $version
     *  1 = $this  >  $version.
     */
    public function compare(VersionInterface $version): int;
}
