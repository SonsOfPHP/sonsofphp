# Setup Command

Initializes or updates `chorale.yaml` by scanning your repository for packages and applying sensible defaults.

## Usage

```bash
chorale setup [options]
```

## Options

- `--project-root=PATH`: Override project root (defaults to current directory)
- `--paths=DIR ...`: Limit discovery to specific paths (relative to root)
- `--discover-only`: Only scan and print results; do not write
- `--write`: Write without confirmation (pair with `--non-interactive` in CI)
- `--non-interactive`: Never prompt for confirmation
- `--accept-all`: Accept suggested adds/renames during interactive runs
- `--strict`: Treat warnings as errors (non‑zero exit)
- `--json`: Emit a machine‑readable JSON report

## Examples

```bash
# Interactive scan and write
chorale setup

# Discover only; do not write any changes
chorale setup --discover-only

# CI‑style non‑interactive write
chorale setup --write --non-interactive

# Limit discovery to a subset of paths
chorale setup --paths src/SonsOfPHP/Component/Cache src/SonsOfPHP/Component/Clock

# Strict mode with JSON report
chorale setup --json --strict > setup-report.json
```

## JSON Report (pretty‑printed)

Example shape of the JSON emitted by `--json` (fields omitted for brevity):

```json
{
  "defaults": {
    "repo_host": "github.com",
    "repo_vendor": "sonsofphp",
    "repo_name_template": "{name:kebab}",
    "default_repo_template": "git@{repo_host}:{repo_vendor}/{name:kebab}.git"
  },
  "groups": {
    "ok": ["src/SonsOfPHP/Component/Cache"],
    "new": ["src/SonsOfPHP/Component/Clock"],
    "renamed": [],
    "drift": [],
    "issues": [],
    "conflicts": []
  },
  "actions": [
    { "type": "add", "path": "src/SonsOfPHP/Component/Clock" }
  ]
}
```
