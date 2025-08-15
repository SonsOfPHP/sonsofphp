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
                $issues[] = "Key '{$k}' must be a string.";
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
                $issues[] = "Key '{$listKey}' must be a list.";
            }
        }

        if (isset($config['patterns']) && is_array($config['patterns'])) {
            foreach ($config['patterns'] as $i => $p) {
                if (!is_array($p)) {
                    $issues[] = "patterns[$i] must be an object.";
                    continue;
                }
                if (!isset($p['match']) || !is_string($p['match'])) {
                    $issues[] = "patterns[$i].match must be a string.";
                }
                foreach (['repo_host','repo_vendor','repo_name_template','repo'] as $k) {
                    if (isset($p[$k]) && !is_string($p[$k])) {
                        $issues[] = "patterns[$i].{$k} must be a string.";
                    }
                }
                foreach (['include','exclude'] as $k) {
                    if (isset($p[$k]) && !is_array($p[$k])) {
                        $issues[] = "patterns[$i].{$k} must be a list of strings.";
                    }
                }
            }
        }

        if (isset($config['targets']) && is_array($config['targets'])) {
            foreach ($config['targets'] as $i => $t) {
                if (!is_array($t)) {
                    $issues[] = "targets[$i] must be an object.";
                    continue;
                }
                foreach (['name','path','repo_host','repo_vendor','repo_name_template','repo'] as $k) {
                    if (isset($t[$k]) && !is_string($t[$k])) {
                        $issues[] = "targets[$i].{$k} must be a string.";
                    }
                }
                foreach (['include','exclude'] as $k) {
                    if (isset($t[$k]) && !is_array($t[$k])) {
                        $issues[] = "targets[$i].{$k} must be a list of strings.";
                    }
                }
            }
        }

        return $issues;
    }
}
