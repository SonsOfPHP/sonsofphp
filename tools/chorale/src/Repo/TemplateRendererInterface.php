<?php

declare(strict_types=1);

namespace Chorale\Repo;

interface TemplateRendererInterface
{
    /**
     * Render a template with {placeholders[:filter[:filter...]]}.
     * Supported vars: repo_host, repo_vendor, repo_name_template, default_repo_template,
     * name, path, tag. Supported filters: lower, upper, kebab, snake, camel, pascal, dot, raw.
     *
     * @param string              $template  e.g. "{repo_host}:{repo_vendor}/{name:kebab}.git"
     * @param array<string, mixed> $vars     e.g. ['repo_host'=>'git@github.com','name'=>'Cookie']
     *
     * @throws \InvalidArgumentException     on unknown placeholder or filter
     */
    public function render(string $template, array $vars): string;

    /**
     * Validate that all placeholders and filters in the template are known.
     * Returns a list of problems; empty array means valid.
     *
     * @return list<string> list of validation messages
     */
    public function validate(string $template): array;
}
