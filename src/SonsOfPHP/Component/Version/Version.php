<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version;

use SonsOfPHP\Component\Version\Exception\VersionException;
use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Version implements VersionInterface, Stringable
{
    private const REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    private int $major;

    private int $minor;

    private int $patch;

    private string $preRelease = '';

    private string $build      = '';

    public function __construct(string $version)
    {
        if (0 === preg_match(self::REGEX, $version, $matches)) {
            throw new VersionException('Invalid Version');
        }

        if (!isset($matches['major']) || !isset($matches['minor']) || !isset($matches['patch'])) {
            throw new VersionException('Invalid Version');
        }

        $this->major = (int) $matches['major'];
        $this->minor = (int) $matches['minor'];
        $this->patch = (int) $matches['patch'];

        if (isset($matches['prerelease'])) {
            $this->preRelease = $matches['prerelease'];
        }

        if (isset($matches['buildmetadata'])) {
            $this->build = $matches['buildmetadata'];
        }
    }

    /**
     * @see self::toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    public static function from(string $version): VersionInterface
    {
        return new self($version);
    }

    public function toString(): string
    {
        $version = sprintf('%d.%d.%d', $this->getMajor(), $this->getMinor(), $this->getPatch());

        if ('' !== $this->getPreRelease()) {
            $version = $version . '-' . $this->getPreRelease();
        }

        if ('' !== $this->getBuild()) {
            $version = $version . '+' . $this->getBuild();
        }

        return $version;
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public function getPatch(): int
    {
        return $this->patch;
    }

    public function getPreRelease(): ?string
    {
        return $this->preRelease;
    }

    public function getBuild(): ?string
    {
        return $this->build;
    }

    public function compare(VersionInterface $version): int
    {
        if ($this->getMajor() > $version->getMajor()) {
            return 1;
        }

        if ($this->getMajor() < $version->getMajor()) {
            return -1;
        }

        if ($this->getMinor() > $version->getMinor()) {
            return 1;
        }

        if ($this->getMinor() < $version->getMinor()) {
            return -1;
        }

        if ($this->getPatch() > $version->getPatch()) {
            return 1;
        }

        if ($this->getPatch() < $version->getPatch()) {
            return -1;
        }

        return 0;
    }

    public function isGreaterThan(VersionInterface $version): bool
    {
        return 1 === $this->compare($version);
    }

    public function isLessThan(VersionInterface $version): bool
    {
        return -1 === $this->compare($version);
    }

    public function isEqualTo(VersionInterface $version): bool
    {
        return 0 === $this->compare($version);
    }

    /**
     * Bumps the patch version by one.
     *
     * @return static
     */
    public function nextPatch(): VersionInterface
    {
        $ver = clone $this;
        ++$ver->patch;

        return $ver;
    }

    /**
     * Bumps the minor version by one.
     *
     * @return static
     */
    public function nextMinor(): VersionInterface
    {
        $ver        = clone $this;
        $ver->patch = 0;
        ++$ver->minor;

        return $ver;
    }

    /**
     * Bumps the major version by one.
     *
     * @return static
     */
    public function nextMajor(): VersionInterface
    {
        $ver        = clone $this;
        $ver->patch = 0;
        $ver->minor = 0;
        ++$ver->major;

        return $ver;
    }
}
