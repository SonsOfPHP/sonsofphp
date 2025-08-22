<?php

declare(strict_types=1);

namespace Chorale\Composer;

use Chorale\Repo\TemplateRenderer;

final readonly class RuleEngine implements RuleEngineInterface
{
    public function __construct(
        private TemplateRenderer $renderer = new TemplateRenderer()
    ) {}

    public function computePackageEdits(array $packageComposer, array $rootComposer, array $config, array $context): array
    {
        $rules = $this->resolveRules($config, $context);
        $edits = [];

        $mirrorKeys     = ['authors','license'];
        $mergeObjectKeys = ['support','funding','extra'];
        $appendKeys     = ['keywords'];
        $maybeKeys      = ['homepage','description'];

        foreach ($mirrorKeys as $key) {
            $this->maybeApplyMirror($edits, $key, $rules, $rootComposer, $packageComposer, $context);
        }

        foreach ($mergeObjectKeys as $key) {
            $this->maybeApplyMergeObject($edits, $key, $rules, $rootComposer, $packageComposer, $context);
        }

        foreach ($appendKeys as $key) {
            $this->maybeApplyAppendUnique($edits, $key, $rules, $rootComposer, $packageComposer, $context);
        }

        foreach ($maybeKeys as $key) {
            $this->maybeApplyMirrorUnless($edits, $key, $rules, $rootComposer, $packageComposer, $context);
        }

        // Allow explicit value overrides to force specific values (wins over rules)
        $overrides = (array) ($context['overrides']['values'] ?? []);
        foreach ($overrides as $key => $value) {
            $rendered = $this->renderIfString($value, $context, $rootComposer);
            if (!$this->equal($packageComposer[$key] ?? null, $rendered)) {
                $edits[$key] = ['__override' => true] + (is_array($rendered) ? $rendered : ['value' => $rendered]);
                // Normalize scalar override shape to direct value
                if (array_key_exists('value', $edits[$key]) && count($edits[$key]) === 2) {
                    $edits[$key] = $edits[$key]['value'];
                }
            }
        }

        return $edits;
    }

    /** @return array<string,string> */
    private function resolveRules(array $config, array $context): array
    {
        // Default is fully opt-in. Nothing is mirrored or merged unless
        // explicitly configured in chorale.yaml or via per-package overrides.
        $defaults = [
            'homepage'    => 'ignore',
            'authors'     => 'ignore',
            'license'     => 'ignore',
            'support'     => 'ignore',
            'funding'     => 'ignore',
            'keywords'    => 'ignore',
            'extra'       => 'ignore',
            'description' => 'ignore',
        ];
        $rootRules = (array) ($config['composer_sync']['rules'] ?? []);
        $overrideRules = (array) ($context['overrides']['rules'] ?? []);
        return array_merge($defaults, $rootRules, $overrideRules);
    }

    /** @param array<string,mixed> $edits */
    private function maybeApplyMirror(array &$edits, string $key, array $rules, array $root, array $pkg, array $ctx): void
    {
        if (($rules[$key] ?? 'ignore') !== 'mirror') {
            return;
        }

        if (!array_key_exists($key, $root)) {
            return;
        }

        $desired = $this->renderIfString($root[$key], $ctx, $root);
        if (!$this->equal($pkg[$key] ?? null, $desired)) {
            $edits[$key] = $desired;
        }
    }

    /** @param array<string,mixed> $edits */
    private function maybeApplyMirrorUnless(array &$edits, string $key, array $rules, array $root, array $pkg, array $ctx): void
    {
        if (($rules[$key] ?? 'ignore') !== 'mirror-unless-overridden') {
            return;
        }

        if (array_key_exists($key, $pkg)) {
            return;
        }

        if (!array_key_exists($key, $root)) {
            return;
        }

        $desired = $this->renderIfString($root[$key], $ctx, $root);
        if (!$this->equal($pkg[$key] ?? null, $desired)) {
            $edits[$key] = $desired;
        }
    }

    /** @param array<string,mixed> $edits */
    private function maybeApplyMergeObject(array &$edits, string $key, array $rules, array $root, array $pkg, array $ctx): void
    {
        if (($rules[$key] ?? 'ignore') !== 'merge-object') {
            return;
        }

        $rootVal = $this->renderIfString($root[$key] ?? null, $ctx, $root);
        $pkgVal  = $pkg[$key] ?? null;
        if (!is_array($rootVal)) {
            return;
        }

        $merged = $this->deepMerge($rootVal, is_array($pkgVal) ? $pkgVal : []);
        if (!$this->equal($pkgVal, $merged)) {
            $edits[$key] = $merged;
        }
    }

    /** @param array<string,mixed> $edits */
    private function maybeApplyAppendUnique(array &$edits, string $key, array $rules, array $root, array $pkg, array $ctx): void
    {
        if (($rules[$key] ?? 'ignore') !== 'append-unique') {
            return;
        }

        $rootVal = $this->renderIfString($root[$key] ?? null, $ctx, $root);
        $pkgVal  = $pkg[$key] ?? null;
        if (!is_array($rootVal)) {
            return;
        }

        $rootList = array_values(array_filter($rootVal, static fn($v): bool => is_string($v) && $v !== ''));
        $pkgList  = is_array($pkgVal) ? array_values(array_filter($pkgVal, static fn($v): bool => is_string($v) && $v !== '')) : [];
        $merged   = array_values(array_unique(array_merge($pkgList, $rootList)));
        sort($merged);
        if (!$this->equal($pkgList, $merged)) {
            $edits[$key] = $merged;
        }
    }

    private function renderIfString(mixed $val, array $ctx, array $root): mixed
    {
        if (!is_string($val)) {
            return $val;
        }

        $vars = [
            'name'        => (string) ($ctx['name'] ?? ''),
            'path'        => (string) ($ctx['path'] ?? ''),
            'repo_vendor' => $this->inferVendorFromRoot($root),
        ];
        return $this->renderer->render($val, $vars);
    }

    private function inferVendorFromRoot(array $root): string
    {
        $name = is_string($root['name'] ?? null) ? $root['name'] : '';
        if (str_contains($name, '/')) {
            return strtolower(substr($name, 0, strpos($name, '/')));
        }

        return '';
    }

    private function deepMerge(array $a, array $b): array
    {
        $out = $a;
        foreach ($b as $k => $v) {
            $out[$k] = is_array($v) && isset($out[$k]) && is_array($out[$k]) ? $this->deepMerge($out[$k], $v) : $v;
        }

        return $out;
    }

    private function equal(mixed $a, mixed $b): bool
    {
        if (is_array($a) && is_array($b)) {
            ksort($a);
            ksort($b);
            foreach ($a as $k => $v) {
                if (!array_key_exists($k, $b)) {
                    return false;
                }

                if (!$this->equal($v, $b[$k])) {
                    return false;
                }
            }

            foreach (array_keys($b) as $k) {
                if (!array_key_exists($k, $a)) {
                    return false;
                }
            }

            return true;
        }

        return $a === $b;
    }
}
