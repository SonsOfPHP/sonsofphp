<?php

declare(strict_types=1);

namespace Chorale\Config;

/**
 * Lightweight validator that checks known keys & types.
 * It ignores $schemaPath for now (no YAML parsing, no ext-yaml dependency).
 */
final class SchemaValidator implements SchemaValidatorInterface
{
    public function validate(array $config, string $schemaPath): array
    {
        $issues = [];

        $strKeys = ['repo_host','repo_vendor','repo_name_template','default_repo_template','default_branch','splitter','tag_strategy'];
        foreach ($strKeys as $k) {
            if (isset($config[$k]) && !is_string($config[$k])) {
                $issues[] = sprintf("Key '%s' must be a string.", $k);
            }
        }

        if (isset($config['rules']) && !is_array($config['rules'])) {
            $issues[] = "Key 'rules' must be an array.";
        } else {
            $rules = (array) ($config['rules'] ?? []);
            if (isset($rules['keep_history']) && !is_bool($rules['keep_history'])) {
                $issues[] = "rules.keep_history must be a boolean.";
            }

            if (isset($rules['skip_if_unchanged']) && !is_bool($rules['skip_if_unchanged'])) {
                $issues[] = "rules.skip_if_unchanged must be a boolean.";
            }

            if (isset($rules['require_files']) && !is_array($rules['require_files'])) {
                $issues[] = "rules.require_files must be an array of strings.";
            }
        }

        foreach (['patterns', 'targets', 'hooks'] as $listKey) {
            if (isset($config[$listKey]) && !is_array($config[$listKey])) {
                $issues[] = sprintf("Key '%s' must be a list.", $listKey);
            }
        }

        if (isset($config['patterns']) && is_array($config['patterns'])) {
            foreach ($config['patterns'] as $i => $p) {
                if (!is_array($p)) {
                    $issues[] = sprintf('patterns[%s] must be an object.', $i);
                    continue;
                }

                if (!isset($p['match']) || !is_string($p['match'])) {
                    $issues[] = sprintf('patterns[%s].match must be a string.', $i);
                }

                foreach (['repo_host','repo_vendor','repo_name_template','repo'] as $k) {
                    if (isset($p[$k]) && !is_string($p[$k])) {
                        $issues[] = sprintf('patterns[%s].%s must be a string.', $i, $k);
                    }
                }

                foreach (['include','exclude'] as $k) {
                    if (isset($p[$k]) && !is_array($p[$k])) {
                        $issues[] = sprintf('patterns[%s].%s must be a list of strings.', $i, $k);
                    }
                }
            }
        }

        if (isset($config['targets']) && is_array($config['targets'])) {
            foreach ($config['targets'] as $i => $t) {
                if (!is_array($t)) {
                    $issues[] = sprintf('targets[%s] must be an object.', $i);
                    continue;
                }

                foreach (['name','path','repo_host','repo_vendor','repo_name_template','repo'] as $k) {
                    if (isset($t[$k]) && !is_string($t[$k])) {
                        $issues[] = sprintf('targets[%s].%s must be a string.', $i, $k);
                    }
                }

                foreach (['include','exclude'] as $k) {
                    if (isset($t[$k]) && !is_array($t[$k])) {
                        $issues[] = sprintf('targets[%s].%s must be a list of strings.', $i, $k);
                    }
                }
            }
        }

        return $issues;
    }
}
