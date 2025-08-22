# Chorale Concepts

Chorale provides a small set of concepts to manage large monorepos predictably.

## Patterns and Targets

- `patterns`: Glob‑like rules that define which directories are considered packages.
  - Supports `*`, `?`, and `**` wildcards.
  - Example: `src/**/Cookie`, `src/SonsOfPHP/*`
- `targets`: Per‑package overrides keyed by path (e.g., repo vendor or template).

## Repository Rendering

Chorale generates repository URLs from a template with placeholders and filters:

- Placeholders: `{name}`, `{repo_host}`, `{repo_vendor}`, `{repo_name_template}`, `{default_repo_template}`, `{path}`, `{tag}`
- Filters: `raw`, `lower`, `upper`, `kebab`, `snake`, `camel`, `pascal`, `dot`
- Example: `{repo_host}:{repo_vendor}/{name:kebab}.git`

## Composer Sync

- Package metadata sync: The rule engine computes `apply` diffs for `composer.json` keys per package.
- Root aggregator: Builds `require`/`replace` maps for the root composer.json.
- Dependency merge: Merges package `require` and `require-dev` into the root, reporting conflicts.
- Delta notation: `+added/-removed/~changed` quickly summarizes map changes.

## Splitting

Split steps are planned when content changes or policy indicates a split is needed. Reasons are printed in the plan output and include content hashes, remote state, and policy options (force, tag strategy, branch).

## Strict Mode

Use `--strict` to make `plan` and `run` exit non‑zero when issues are detected (missing root version, dependency conflicts, etc.). This is useful in CI to gate merges.

