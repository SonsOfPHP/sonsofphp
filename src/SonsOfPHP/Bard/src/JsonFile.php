<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard;

use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * Used to manage bard.json and composer.json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class JsonFile extends \SplFileInfo implements JsonFileInterface
{
    private array $config = [];

    private bool $loaded = false;

    /**
     * Loads the json file so it can be processed.
     */
    private function load(): void
    {
        if ($this->loaded) {
            return;
        }

        // @todo Check file exists and is readable

        $this->config = json_decode(file_get_contents($this->getRealPath()), true);
        $this->loaded = true;
    }

    public function getConfig(): array
    {
        $this->load();

        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->load();

        $this->config = $config;
    }

    /**
     * Grabs and returns a section from the JSON file
     *
     * @return array|int|string|null
     */
    public function getSection(string $section): mixed
    {
        $this->load();

        return $this->config[$section] ?? null;
    }

    /**
     * @param array|int|string|null $value
     */
    public function setSection(string $section, $value): self
    {
        $this->load();

        $clone                   = clone $this;
        $clone->config[$section] = $value;

        return $clone;
    }

    public function toJson(): string
    {
        $this->load();

        return json_encode($this->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * $jsonFile->with(new ExampleOperator());
     */
    public function with(OperationInterface $operator): self
    {
        return $operator->apply($this);
    }

    public function save(): void
    {
        $this->load();

        // @todo check file is writeable

        file_put_contents($this->getRealPath(), $this->toJson());
    }
}
