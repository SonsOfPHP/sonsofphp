# Run Command

Builds a plan and applies it in one step. Equivalent to `chorale plan` followed by `chorale apply`.

## Usage

```bash
chorale run [options]
```

## Options

- `--project-root=PATH`: Override project root (defaults to current directory)
- `--paths=DIR ...`: Limit discovery to specific package paths
- `--force-split`: Plan split steps even if content appears unchanged
- `--verify-remote`: Verify remote state if lockfiles are missing/stale
- `--strict`: Exit non‑zero on issues (e.g., missing root version, conflicts)
- `--show-all`: Include no‑op summaries in output

## Examples

```bash
# Standard run
chorale run

# Limit scope and enable strict mode
chorale run --paths src/SonsOfPHP/Component/Cache --strict
```
