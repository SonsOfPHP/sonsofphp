# Agents Guide for Sons of PHP Monorepo

This repository is a PHP monorepo containing many packages under `src/`. This guide provides consistent instructions for AI coding agents to work safely and effectively across the codebase.

## Repo Layout

- Root: build tooling (`Makefile`, composer), shared configs, CI inputs.
- Code: packages live under `src/SonsOfPHP/*`, typically with `src/` and `Tests/` subfolders.
- Docs: developer documentation in `docs/` with a GitBook-style `SUMMARY.md`.
- Tools: development tools vendored under `tools/` (phpunit, psalm, rector, php-cs-fixer, etc.).

## Ground Rules

- Prefer minimal, targeted changes; avoid refactors beyond the task scope.
- Never edit anything under any `vendor/` directory or generated artifacts like `dist/`.
- Maintain backward compatibility for public APIs unless explicitly instructed otherwise.
- Update relevant docs under `docs/` when behavior or public APIs change.
- Keep code style consistent; use provided tooling to format, lint, and check types.

## Setup

- Install dependencies once at the repo root:
  - `make install`

## Common Tasks

- Run tests (entire repo):
  - `make test`
- Run tests (limit to a package):
  - `PHPUNIT_OPTIONS='path/to/package/Tests' make test`
- Code style (dry-run):
  - `make php-cs-fixer`
- Static analysis (Psalm):
  - `make psalm`
- Rector & style upgrades (may modify files):
  - `make upgrade-code`
- Lint PHP syntax:
  - `make lint`
- Coverage report:
  - `make coverage`

## When Editing a Package

- Work inside that package directory (e.g. `src/SonsOfPHP/Component/Clock`).
- Put new source under that package’s `src/`; add tests under its `Tests/`.
- Use the package-focused test command above to tighten feedback cycles.

## Documentation

- Update `docs/` to reflect user-facing changes.
- Add or modify the most relevant page (e.g., `docs/components/*.md`, `docs/contracts/*.md`, or `docs/symfony-bundles/*.md`).
- If adding a new page, ensure it’s listed in `docs/SUMMARY.md`.

## Roadmap

- The project roadmap lives in `ROADMAP.md` at the repository root.
- Remove completed items from the roadmap as part of the related change.

## Pull Request Checklist

- Build passes: `make test` (optionally with coverage).
- Code quality passes: `make php-cs-fixer`, `make psalm`, and (if applicable) `make upgrade-code`.
- Docs updated where needed.
- No changes to `vendor/` or generated artifacts.

