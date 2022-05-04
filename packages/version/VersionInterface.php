<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version;

use SonsOfPHP\Component\Version\Exception\VersionException;

/**
 * Version Interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface VersionInterface
{
    /**
     * $version = Version::from('1.2.3');
     *
     * @param string $version
     *
     * @throws VersionException
     *
     * @return static
     */
    public static function from(string $version): VersionInterface;

    /**
     * Returns the version
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the Major Version
     *
     * @return int
     */
    public function getMajor(): int;

    /**
     * Returns the Minor Version
     *
     * @return int
     */
    public function getMinor(): int;

    /**
     * Returns the Patch Version
     *
     * @return int
     */
    public function getPatch(): int;

    /**
     * Returns the Pre-release (if any)
     *
     * @return string
     */
    public function getPreRelease(): ?string;

    /**
     * Returns the Build Metadata (if any)
     *
     * @return string
     */
    public function getBuild(): ?string;

    /**
     * Returns
     * -1 = $this  <  $version
     *  0 = $this === $version
     *  1 = $this  >  $version
     *
     * @param VersionInterface $version
     *
     * @return int
     */
    public function compare(VersionInterface $version): int;
}
