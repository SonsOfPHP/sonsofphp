# Chorale Plan Command

The `plan` command is an advanced dry‑run that inspects your monorepo and prints exactly what would change — without writing anything. Use it to understand and validate changes before applying them.

## Summary

- Checks package versions against the monorepo root version
- Computes package `composer.json` edits via the rule engine
- Updates the root `composer.json` (require/replace) as an aggregator
- Merges package dependencies into the root (with conflicts reported)
- Decides which packages need a split (and why)

## Usage

```bash
chorale plan [<vendor/name>] [options]
```

- `<vendor/name>`: Optional composer package name to focus on a single package
  (e.g., `sonsofphp/cache`). This reduces noise for large monorepos.

## Options

- Verbosity levels control detail:
  - default: concise one‑line summaries
  - `-v`: detailed blocks (per‑section details)
  - `-vv`: detailed blocks + no‑op summaries
  - `-vvv`: everything above plus full JSON plan printed at the end
- `--composer-only`: Only include composer-related steps; exclude split steps
- `--show-all`: Include no‑op summaries (same as `-vv` or higher)
- `--json`: Output as JSON; ideal for `apply` or external tooling
- `--project-root=PATH`: Explicit project root (defaults to current directory)
- `--paths=DIR ...`: Limit discovery to specific package path(s)
- `--force-split`: Plan split steps even if content appears unchanged
- `--verify-remote`: Verify remote state if local lockfiles are missing/stale
- `--strict`: Exit non‑zero when issues are detected (e.g., conflicts, missing root version)

## Examples

```bash
# Concise one‑liners (default)
chorale plan

# Detailed output
chorale plan -v

# Detailed + show no‑ops
chorale plan -vv

# Show full JSON at the end (also printed as human output first)
chorale plan -vvv

# JSON output for apply
chorale plan --json > plan.json

# Focus on one package by composer name
chorale plan sonsofphp/cache

# Focused + detailed
chorale plan sonsofphp/cache -v

# Limit discovery to a folder (path)
chorale plan --paths src/SonsOfPHP/Component/Cache
```

## Output Breakdown

Plan output is grouped by step type:

- `Split steps`: Shows package path → repo, splitter, branch, tag strategy, and reasons
- `Package versions`: Shows the package name, target version, previous version, and reason
- `Package metadata`: Lists keys to mirror and pretty‑prints the exact `apply` JSON block
- `Root composer: aggregator`: Prints the final `require` and `replace` maps
- `Root composer: dependency merge`: Prints merged `require` and `require‑dev`, plus conflicts
- `Root composer: maintenance`: Maintenance actions like `validate`

### Delta Notation

Composer maps show a delta summary:

```
[+added/-removed/~changed]
```

This is printed inline in summaries and also included in JSON under `meta` as
`delta_require`, `delta_replace`, and `delta_require_dev` where applicable.

## JSON Schema (version 1)

The `--json` output contains:

- `steps`: Array of step objects with type‑specific fields
- `noop`: Optional summary of skipped groups when `--show-all` is used
- `meta`: For root steps, delta objects summarizing changes

Example snippet (root update):

```json
{
  "type": "composer-root-update",
  "root": "sonsofphp/monorepo",
  "root_version": "1.2.3",
  "require": {
    "sonsofphp/cache": "1.2.3"
  },
  "replace": {
    "sonsofphp/cache": "1.2.3"
  },
  "meta": {
    "delta_require": {
      "added": 1,
      "removed": 0,
      "changed": 0
    },
    "delta_replace": {
      "added": 1,
      "removed": 0,
      "changed": 0
    }
  }
}
```

## Tips

- Combine the positional `<vendor/name>` with `--json` to isolate and export a single package’s plan.
- For CI checks, run with `--strict` so the command exits non‑zero when action is required.
