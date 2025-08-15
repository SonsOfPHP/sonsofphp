<?php

declare(strict_types=1);

namespace Chorale\Config;

final class ConfigDefaults implements ConfigDefaultsInterface
{
    /** @var array<string, mixed> */
    private array $fallbacks = [
        'repo_host' => 'git@github.com',
        'repo_vendor' => 'SonsOfPHP',
        'repo_name_template' => '{name:kebab}.git',
        'default_repo_template' => '{repo_host}:{repo_vendor}/{repo_name_template}',
        'default_branch' => 'main',
        'splitter' => 'splitsh',
        'tag_strategy' => 'inherit-monorepo-tag',
        'rules' => [
            'keep_history' => true,
            'skip_if_unchanged' => true,
            'require_files' => ['composer.json', 'LICENSE'],
        ],
    ];

    public function resolve(array $config): array
    {
        $out = $this->fallbacks;

        foreach (array_keys($this->fallbacks) as $k) {
            if (array_key_exists($k, $config)) {
                if ($k === 'rules') {
                    $out['rules'] = array_merge($out['rules'], (array) $config['rules']);
                } else {
                    $out[$k] = (string) $config[$k];
                }
            }
        }

        // If the template explicitly provided, keep it;
        // otherwise compute from the resolved parts.
        if (!isset($config['default_repo_template']) || $config['default_repo_template'] === '') {
            $out['default_repo_template'] = sprintf(
                '%s:%s/%s',
                $out['repo_host'],
                $out['repo_vendor'],
                $out['repo_name_template']
            );
        }

        return $out;
    }
}
