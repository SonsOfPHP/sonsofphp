<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version;

use SonsOfPHP\Component\Version\Exception\VersionException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Version implements VersionInterface
{
    private const REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    private int $major;
    private int $minor;
    private int $patch;
    private string $preRelease = '';
    private string $build = '';

    /**
     * @param string $version
     */
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
            $this->preRelease = (string) $matches['prerelease'];
        }

        if (isset($matches['buildmetadata'])) {
            $this->build = (string) $matches['buildmetadata'];
        }
    }

    /**
     * @see self::toString()
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public static function from(string $version): VersionInterface
    {
        return new static($version);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $version = sprintf('%d.%d.%d', $this->getMajor(), $this->getMinor(), $this->getPatch());

        if ($this->getPreRelease()) {
            $version = $version.'-'.$this->getPreRelease();
        }

        if ($this->getBuild()) {
            $version = $version.'+'.$this->getBuild();
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getMajor(): int
    {
        return $this->major;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinor(): int
    {
        return $this->minor;
    }

    /**
     * {@inheritdoc}
     */
    public function getPatch(): int
    {
        return $this->patch;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreRelease(): ?string
    {
        return $this->preRelease;
    }

    /**
     * {@inheritdoc}
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * {@inheritdoc}
     */
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
}
