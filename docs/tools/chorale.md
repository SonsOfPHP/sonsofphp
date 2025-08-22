# Chorale

Chorale is a CLI tool for managing PHP monorepos. It uses a plan/apply workflow to keep package metadata and the root package in sync.

## Installation

```bash
cd tools/chorale
composer install
```

## Usage

Run the commands from the project root:

```bash
# create chorale.yaml by scanning packages
chorale setup

# preview changes without modifying files
chorale plan --json > plan.json

# apply an exported plan
chorale apply --file plan.json

# build and apply a plan in one go
chorale run
```

Chorale automatically merges all package `composer.json` files into the root `composer.json` so the monorepo can be installed as a single package. Any dependency conflicts are recorded under the `extra.chorale.dependency-conflicts` section for review.

## Commands

- `setup` – generate configuration and validate required files.
- `plan` – build a plan for splitting packages and root updates.
- `run` – build and immediately apply a plan.
- `apply` – execute steps from a JSON plan file.

See also:
- Commands: tools/chorale/commands/README.md
- Core concepts: tools/chorale/concepts.md
- Configuration and chorale.yaml: tools/chorale/config.md
- Composer mirroring and overrides: tools/chorale/mirroring.md
