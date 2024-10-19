<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface JsonFileInterface
{
    /**
     * @return string
     */
    public function getFilename();

    /**
     * Grabs and returns a section from the JSON file
     *
     * @return array|int|string|null
     */
    public function getSection(string $section): mixed;

    /**
     * @param array|int|string|null $value
     */
    public function setSection(string $section, $value): self;
}
