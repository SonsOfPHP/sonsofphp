# Rule Matrix & Examples

This page shows concrete, side‑by‑side examples of how each Chorale rule transforms package `composer.json` values using the root `composer.json` as input.

Notes
- Default is opt‑in: a key does nothing unless a rule is configured.
- Per‑package overrides win over rules (via `targets[].composer_overrides`).
- String values support simple template rendering in some cases (e.g., overrides): `{name}`, `{path}`, `{repo_vendor}`.

## mirror

Copies the root value into each package (package value is replaced).

Rule

```yaml
composer_sync:
  rules:
    authors: mirror
    license: mirror
```

Input → Output

Root excerpt

```json
{
  "authors": [{ "name": "Sons of PHP" }],
  "license": "MIT"
}
```

Package before

```json
{
  "authors": [{ "name": "Someone Else" }],
  "license": "Proprietary"
}
```

Package after (planned edits)

```json
{
  "authors": [{ "name": "Sons of PHP" }],
  "license": "MIT"
}
```

## mirror-unless-overridden

Uses the root value only when the package does not define the key.

Rule

```yaml
composer_sync:
  rules:
    homepage: mirror-unless-overridden
    description: mirror-unless-overridden
```

Case A — package missing key (mirrors from root)

Root excerpt

```json
{ "homepage": "https://sonsofphp.com" }
```

Package before

```json
{ }
```

Package after

```json
{ "homepage": "https://sonsofphp.com" }
```

Case B — package has key (no change)

Package before

```json
{ "homepage": "https://example.test/pkg" }
```

Package after

```json
{ "homepage": "https://example.test/pkg" }
```

## merge-object

Deep‑merges the root object into the package object. Package values override root on conflicts; nested objects are merged recursively.

Rule

```yaml
composer_sync:
  rules:
    support: merge-object
    funding: merge-object
    extra:   merge-object
```

Input → Output

Root excerpt

```json
{
  "support": {
    "issues": "https://github.com/sonsofphp/monorepo/issues",
    "docs":   "https://docs.sonsofphp.com"
  },
  "extra": {
    "branch-alias": { "dev-main": "1.x-dev" }
  }
}
```

Package before

```json
{
  "support": { "issues": "https://tracker.example/pkg" },
  "extra":   { "branch-alias": { "dev-main": "2.x-dev" }, "mark": true }
}
```

Package after

```json
{
  "support": {
    "issues": "https://tracker.example/pkg",   
    "docs":   "https://docs.sonsofphp.com"     
  },
  "extra": {
    "branch-alias": { "dev-main": "2.x-dev" }, 
    "mark": true                                  
  }
}
```

## append-unique

Appends unique strings from the root array into the package array, removes empties, de‑duplicates, and sorts the result alphabetically.

Rule

```yaml
composer_sync:
  rules:
    keywords: append-unique
```

Input → Output

Root excerpt

```json
{ "keywords": ["php", "monorepo", "tooling"] }
```

Package before

```json
{ "keywords": ["cache", "php", ""] }
```

Package after (sorted unique)

```json
{ "keywords": ["cache", "monorepo", "php", "tooling"] }
```

## ignore

Does nothing for the key; no planned edits.

Rule

```yaml
composer_sync:
  rules:
    description: ignore
```

Input → Output

Root excerpt

```json
{ "description": "Shared default description" }
```

Package before

```json
{ "description": "Specific package description" }
```

Package after

```json
{ "description": "Specific package description" }
```

## Per‑package overrides (values and rules)

Overrides force values or tweak rules for a single package; overrides take precedence.

Example

```yaml
targets:
  - path: src/SonsOfPHP/Component/Cache
    composer_overrides:
      values:
        description: "{name} component for Sons of PHP"
      rules:
        homepage: mirror
```

Effect
- `description`: Always set to the rendered string, regardless of `composer_sync.rules`.
- `homepage`: Uses `mirror` for this package only, even if globally configured as `mirror-unless-overridden`.

