<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard;

/**
 * Used to manage bard.json and composer.json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class JsonFile
{
    private array $config = [];

    public function __construct(
        private string $filename,
    ) {
        $this->load();
    }

    private function load(): void
    {
        $this->config = json_decode(file_get_contents($this->filename), true);
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
        if ([] === $this->config) {
            $this->load();
        }

        return $this->config[$section] ?? null;
    }

    /**
     */
    public function setSection(string $section, $value): self
    {
        if ([] === $this->config) {
            $this->load();
        }

        $clone                   = clone $this;
        $clone->config[$section] = $value;

        return $clone;
    }

    public function toJson(): string
    {
        return json_encode($this->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * $jsonFile->with(new ExampleOperator());
     */
    public function with($operator): self
    {
        return $operator->apply($this);
    }

    public function save(): void
    {
        file_put_contents($this->filename, $this->toJson());
    }
}
