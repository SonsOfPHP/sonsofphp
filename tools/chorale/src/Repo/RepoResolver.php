<?php

declare(strict_types=1);

namespace Chorale\Repo;

use Chorale\Util\PathUtilsInterface;

final class RepoResolver implements RepoResolverInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly PathUtilsInterface $paths,
    ) {}

    public function resolve(array $defaults, array $pattern, array $target, string $path, ?string $name = null): string
    {
        // scope vars: target > pattern > defaults
        $vars = [
            'repo_host'           => $target['repo_host']           ?? $pattern['repo_host']           ?? $defaults['repo_host'],
            'repo_vendor'         => $target['repo_vendor']         ?? $pattern['repo_vendor']         ?? $defaults['repo_vendor'],
            'repo_name_template'  => $target['repo_name_template']  ?? $pattern['repo_name_template']  ?? $defaults['repo_name_template'],
            'default_repo_template' => $defaults['default_repo_template'],
            'name'                => $name ?? $this->paths->leaf($path),
            'path'                => $path,
            'tag'                 => '', // filled by plan/apply, not needed for setup
        ];

        // choose template: explicit repo wins, then pattern.repo, else default_repo_template
        $tpl = $target['repo']
            ?? $pattern['repo']
            ?? (string) $defaults['default_repo_template'];

        // validate template separately is done by TemplateRenderer; here we render confidently
        return $this->renderer->render($tpl, $vars);
    }
}
