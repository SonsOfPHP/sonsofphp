<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard;

use SonsOfPHP\Component\Json\Json;

/**
 * Used to manage bard.json and composer.json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class JsonFile
{
    private array $config = [];
    private Json $json;

    public function __construct(
        private string $filename,
    ) {
        $this->json     = new Json();
        $this->load();
    }

    private function load(): void
    {
        $this->config = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($this->filename));
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Grabs and returns a section from the JSON file
     *
     * @return array|int|string|null
     */
    public function getSection(string $section): mixed
    {
        if (!isset($this->config)) {
            $this->load();
        }

        if (isset($this->config[$section])) {
            return $this->config[$section];
        }

        return null;
    }

    /**
     */
    public function setSection(string $section, $value): self
    {
        if (!isset($this->config)) {
            $this->load();
        }

        $clone                   = clone $this;
        $clone->config[$section] = $value;

        return $clone;
    }

    /**
     */
    public function toJson(): string
    {
        return $this->json->getEncoder()
            ->prettyPrint()
            ->unescapedUnicode()
            ->unescapedSlashes()
            ->encode($this->config);
    }

    /**
     * $operator = new Operator();
     * $jsonFile->with(new ExampleOperator());
     */
    public function with($operator): self
    {
        return $operator->apply($this);
    }
}
