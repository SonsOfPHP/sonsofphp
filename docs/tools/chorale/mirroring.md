# Mirroring & Overrides

See also: [Rule Matrix & Examples](rules-matrix.md) for side‑by‑side input→output transformations of each rule.

Chorale can mirror and merge selected keys from the root `composer.json` into each package’s `composer.json`.
This is fully opt‑in and controlled through `chorale.yaml` rules and per‑package overrides.

## Default Behavior (Opt‑in)

- By default, nothing is mirrored or merged — everything is `ignore`.
- To enable behavior, set `composer_sync.rules` in `chorale.yaml`.
- Per‑package overrides can force values or rules for a single package.

## Supported Keys and Behaviors

- `mirror`: copy the root’s value into the package
- `mirror-unless-overridden`: use the root’s value only if the package doesn’t define it
- `merge-object`: deep‑merge objects (e.g., `support`, `funding`, `extra`)
- `append-unique`: append unique items for arrays of strings (e.g., `keywords`)
- `ignore`: do nothing

## Examples

### Mirror authors and license

```yaml
composer_sync:
  rules:
    authors: mirror
    license: mirror
```

### Merge support and funding

```yaml
composer_sync:
  rules:
    support: merge-object
    funding: merge-object
```

### Append unique keywords

```yaml
composer_sync:
  rules:
    keywords: append-unique
```

### Prefer package’s own homepage, otherwise mirror root

```yaml
composer_sync:
  rules:
    homepage: mirror-unless-overridden
```

## Per‑package Overrides

Use `targets[].composer_overrides` to force specific values or rule behavior for one package.

```yaml
targets:
  - path: src/SonsOfPHP/Component/Cache
    composer_overrides:
      values:
        description: "Sons of PHP Cache component"
      rules:
        homepage: mirror
```

- `values`: Explicit values; can use template placeholders like `{name}`
- `rules`: Per‑key rule overrides (e.g., force `homepage` to `mirror` for this package only)

## Prevent Mirroring for a Package

- Do not include the key in `composer_sync.rules`, or set it to `ignore`.
- For a specific package, set `targets[].composer_overrides.rules.<key>: ignore`.

## JSON Output & Deltas

- `plan --json` includes the exact `apply` object per package.
- Root steps include `meta.delta_*` with counts for added/removed/changed.
