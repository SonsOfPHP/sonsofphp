<?php

declare(strict_types=1);

namespace Chorale\Repo;

/**
 * Renders string templates with variables and filters.
 *
 * Placeholders: {name}, {repo_host}, {repo_vendor}, {repo_name_template}, {default_repo_template}, {path}, {tag}
 * Filters: raw, lower, upper, kebab, snake, camel, pascal, dot
 *
 * Examples:
 * - render('{repo_host}:{repo_vendor}/{name:kebab}.git', ['repo_host'=>'git@github.com','repo_vendor'=>'Acme','name'=>'My Lib'])
 *   => 'git@github.com:Acme/my-lib.git'
 */
final class TemplateRenderer implements TemplateRendererInterface
{
    /** @var array<string, true> */
    private array $allowedVars = [
        'repo_host'             => true,
        'repo_vendor'           => true,
        'repo_name_template'    => true,
        'default_repo_template' => true,
        'name'                  => true,
        'path'                  => true,
        'tag'                   => true,
    ];

    /** @var array<string, callable(string):string> */
    private array $filters;

    public function __construct()
    {
        $this->filters = [
            // @todo is "raw" needed?
            'raw' => static fn(string $s): string => $s,
            'lower' => static fn(string $s): string => mb_strtolower($s),
            'upper' => static fn(string $s): string => mb_strtoupper($s),
            'kebab' => static fn(string $s): string => self::toKebab($s),
            'snake' => static fn(string $s): string => self::toSnake($s),
            'camel' => static fn(string $s): string => self::toCamel($s),
            'pascal' => static fn(string $s): string => self::toPascal($s),
            'dot' => static fn(string $s): string => str_replace(['_', ' ', '-'], '.', self::basicWords($s)),
        ];
    }

    public function render(string $template, array $vars): string
    {
        // Support nested placeholders by iteratively expanding until stable.
        // e.g. "{repo_host}:{repo_vendor}/{repo_name_template}"
        // where repo_name_template = "{name:kebab}.git"
        // will fully resolve to ".../cookie.git".
        $out = $template;
        $maxPasses = 5; // avoid infinite loops
        for ($i = 0; $i < $maxPasses; $i++) {
            $issues = $this->validate($out);
            if ($issues !== []) {
                throw new \InvalidArgumentException('Invalid template: ' . implode('; ', $issues));
            }

            $next = preg_replace_callback(
                '/\{([a-zA-Z_]\w*)(?::([a-zA-Z:]+))?\}/',
                function (array $m) use ($vars): string {
                    $var = $m[1];
                    $filters = isset($m[2]) ? explode(':', $m[2]) : [];
                    $value = (string) ($vars[$var] ?? '');
                    foreach ($filters as $f) {
                        if ($f === '') {
                            continue;
                        }

                        /** @var callable(string):string $fn */
                        $fn = $this->filters[$f] ?? null;
                        if ($fn === null) {
                            // validate() would have caught this; keep defensive anyway
                            throw new \InvalidArgumentException(sprintf("Unknown filter '%s'", $f));
                        }

                        $value = $fn($value);
                    }

                    return $value;
                },
                $out
            );
            if ($next === null) {
                // regex error; fall back to current output
                break;
            }

            if ($next === $out || in_array(preg_match('/\{[a-zA-Z_]\w*(?::[a-zA-Z:]+)?\}/', $next), [0, false], true)) {
                $out = $next;
                break; // stabilized or no more placeholders
            }

            $out = $next;
        }

        return $out;
    }

    public function validate(string $template): array
    {
        $issues = [];
        if ($template === '') {
            return $issues;
        }

        if (in_array(preg_match_all('/\{([a-zA-Z_]\w*)(?::([a-zA-Z:]+))?\}/', $template, $matches, \PREG_SET_ORDER), [0, false], true)) {
            return $issues;
        }

        foreach ($matches as $match) {
            $var = $match[1];
            $filterStr = $match[2] ?? '';

            if (!isset($this->allowedVars[$var])) {
                $issues[] = sprintf("Unknown placeholder '%s'", $var);
            }

            if ($filterStr !== '') {
                foreach (explode(':', $filterStr) as $f) {
                    if ($f === '') {
                        continue;
                    }

                    if (!isset($this->filters[$f])) {
                        $issues[] = sprintf("Unknown filter '%s' for '%s'", $f, $var);
                    }
                }
            }
        }

        return $issues;
    }

    private static function toKebab(string $s): string
    {
        return str_replace('_', '-', self::toWordsLower($s));
    }

    private static function toSnake(string $s): string
    {
        return str_replace('-', '_', self::toWordsLower($s, '_'));
    }

    private static function toCamel(string $s): string
    {
        $words = self::basicWords($s);
        $words = preg_split('/[ \-_\.]+/u', $words) ?: [];

        $out = '';
        foreach ($words as $i => $w) {
            $w = mb_strtolower($w);
            $out .= $i === 0 ? $w : mb_strtoupper(mb_substr($w, 0, 1)) . mb_substr($w, 1);
        }

        return $out;
    }

    private static function toPascal(string $s): string
    {
        $camel = self::toCamel($s);
        return mb_strtoupper(mb_substr($camel, 0, 1)) . mb_substr($camel, 1);
    }

    /** Normalize to word separators as spaces for filtering. */
    private static function basicWords(string $s): string
    {
        // Split camelCase/PascalCase
        $s = preg_replace('/(?<!^)([A-Z])/u', ' $1', $s) ?? $s;
        // Normalize separators to spaces
        return str_replace(['_', '-', '.'], ' ', $s);
    }

    private static function toWordsLower(string $s, string $glue = '-'): string
    {
        $w = preg_split('/[ \-_\.]+/u', self::basicWords($s)) ?: [];
        $w = array_map(static fn(string $x): string => mb_strtolower($x), $w);
        return implode($glue, array_filter($w, static fn(string $x): bool => $x !== ''));
    }
}
