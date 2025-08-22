# Apply Command

Applies steps from a JSON plan previously exported by `chorale plan --json`.

## Usage

```bash
chorale apply --file plan.json [--project-root PATH]
```

## Options

- `--project-root=PATH`: Override project root (defaults to current directory)
- `--file=FILE` (or `-f`): Path to JSON plan file (default: `plan.json`)

## Examples

```bash
# Apply a plan from the current directory
chorale apply --file plan.json

# Apply a plan from a different root
chorale apply --project-root /path/to/repo --file /tmp/plan.json
```

## Plan File Format (pretty‑printed)

The plan file is the JSON output produced by `chorale plan --json`:

```json
{
  "version": 1,
  "steps": [
    {
      "type": "package-version-update",
      "name": "sonsofphp/cache",
      "version": "1.2.3"
    }
  ],
  "noop": {}
}
```
