# Chorale Configuration (chorale.yaml)

Chorale is opt‑in by default. It does nothing unless you configure it in `chorale.yaml`.
This keeps behavior explicit and developer‑friendly for large monorepos.

## File Location

Place `chorale.yaml` at the repository root (next to the root `composer.json`).

## Top‑Level Keys

- `patterns`: List of discovery patterns for packages (glob‑like)
- `targets`: Per‑package overrides keyed by `path`
- `composer_sync`: Rules for mirroring and merging composer keys (opt‑in)
- `split`: Settings used by the split decider (ignore globs, etc.)

## Patterns

Example:

```yaml
patterns:
  - match: "src/SonsOfPHP/*"
  - match: "src/SonsOfPHP/Component/*"
```

## Targets

Targets let you override values for specific packages:

```yaml
targets:
  - path: "src/SonsOfPHP/Component/Cache"
    repo_vendor: SonsOfPHP
    composer_overrides:
      values:
        description: "{name} component for the Sons of PHP monorepo"
      rules:
        homepage: mirror-unless-overridden
```

## Composer Sync (opt‑in)

By default, nothing is mirrored or merged. You must opt in by setting `composer_sync.rules`.

```yaml
composer_sync:
  rules:
    authors: mirror          # copy from root composer.json
    license: mirror          # copy from root composer.json
    support: merge-object    # deep-merge objects from root into package
    funding: merge-object
    keywords: append-unique  # append unique strings from root
    homepage: mirror-unless-overridden # mirror only if package doesn’t set it
    description: ignore      # never mirror
```

Available rule values:
- `mirror`: force package value to match root
- `mirror-unless-overridden`: use root value only if the package doesn’t define one
- `merge-object`: deep‑merge root object into package object
- `append-unique`: append unique items from root list
- `ignore`: do nothing

## Split Settings

```yaml
split:
  ignore:
    - "vendor/**"
    - "**/composer.lock"
    - "**/.DS_Store"
```

## Complete Example

A cohesive `chorale.yaml` showing common settings together:

```yaml
patterns:
  - match: "src/SonsOfPHP/*"
  - match: "src/SonsOfPHP/Component/*"

targets:
  - path: "src/SonsOfPHP/Component/Cache"
    repo_vendor: SonsOfPHP
    composer_overrides:
      values:
        description: "{name} component for the Sons of PHP monorepo"
      rules:
        homepage: mirror
  - path: "src/SonsOfPHP/Component/Clock"
    composer_overrides:
      values:
        description: "Clock utilities for Sons of PHP"

composer_sync:
  rules:
    authors: mirror
    license: mirror
    support: merge-object
    funding: merge-object
    extra: merge-object
    keywords: append-unique
    homepage: mirror-unless-overridden
    description: ignore

split:
  ignore:
    - "vendor/**"
    - "**/composer.lock"
    - "**/.DS_Store"
```

## Notes

- Overrides win over rules for specific packages (see Mirroring & Overrides).
- Patterns determine discovery roots; paths let you limit plan scope quickly.
- Use `plan --strict` in CI to require explicit action when needed.
