<?php

declare(strict_types=1);

namespace Chorale\Config;

use Chorale\Util\SortingInterface;

final class ConfigNormalizer implements ConfigNormalizerInterface
{
    public function __construct(
        private readonly SortingInterface $sorting,
        private readonly ConfigDefaultsInterface $defaults
    ) {}

    public function normalize(array $config): array
    {
        $def = $this->defaults->resolve($config);

        // drop redundant overrides in patterns
        $patterns = (array) ($config['patterns'] ?? []);
        foreach ($patterns as &$p) {
            $p = (array) $p;
            foreach (['repo_host','repo_vendor','repo_name_template'] as $k) {
                if (isset($p[$k]) && (string) $p[$k] === (string) $def[$k]) {
                    unset($p[$k]);
                }
            }
        }
        unset($p);
        $patterns = $this->sorting->sortPatterns($patterns);

        // drop redundant overrides in targets
        $targets = (array) ($config['targets'] ?? []);
        foreach ($targets as &$t) {
            $t = (array) $t;
            foreach (['repo_host','repo_vendor','repo_name_template'] as $k) {
                if (isset($t[$k]) && (string) $t[$k] === (string) $def[$k]) {
                    unset($t[$k]);
                }
            }
        }
        unset($t);
        $targets = $this->sorting->sortTargets($targets);

        // Rebuild config with stable top-level key order
        $out = [
            'version' => $config['version'] ?? 1,
            'repo_host' => $def['repo_host'],
            'repo_vendor' => $def['repo_vendor'],
            'repo_name_template' => $def['repo_name_template'],
            'default_repo_template' => $def['default_repo_template'],
            'default_branch' => $def['default_branch'],
            'splitter' => $def['splitter'],
            'tag_strategy' => $def['tag_strategy'],
            'rules' => $def['rules'],
        ];
        if ($patterns !== []) {
            $out['patterns'] = $patterns;
        }
        if ($targets  !== []) {
            $out['targets']  = $targets;
        }
        if (!empty($config['hooks'])) {
            $out['hooks'] = array_values((array) $config['hooks']);
        }

        return $out;
    }
}
