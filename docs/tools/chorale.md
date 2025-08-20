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
php bin/chorale setup

# preview changes without modifying files
php bin/chorale plan --json > plan.json

# apply an exported plan
php bin/chorale apply --file plan.json

# build and apply a plan in one go
php bin/chorale run
```

Chorale automatically merges all package `composer.json` files into the root `composer.json` so the monorepo can be installed as a single package. Any dependency conflicts are recorded under the `extra.chorale.dependency-conflicts` section for review.

## Commands

- `setup` – generate configuration and validate required files.
- `plan` – build a plan for splitting packages and root updates.
- `run` – build and immediately apply a plan.
- `apply` – execute steps from a JSON plan file.
