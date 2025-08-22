# AGENTS

Chorale is a CLI tool maintained in this repository.

- Use descriptive variable names and document public methods.
- Add unit tests for new features in `src/Tests`.
- Run `composer install` and `./vendor/bin/phpunit` in this directory before committing changes.

## Documentation Discipline

- When changing command flags or behavior, update the long `--help` text and
  the docs under `docs/tools/chorale/*` (Plan, Concepts, Configuration,
  Mirroring & Overrides). Add new pages to `docs/SUMMARY.md`.
- Mirroring is opt‑in. Ensure examples and defaults in docs reflect that.

### Notes for Docblocks and Examples

- When documenting glob patterns inside PHP block comments, avoid using the exact sequence `*/` which terminates the comment. Prefer escaping as `*\/` (e.g., `src/*\/Lib`), or insert a space, to keep examples readable and parsable.

## Roadmap

- Implement executors for remaining plan steps such as composer root rebuild and metadata sync.
- Improve conflict resolution strategies for dependency merges.
- Enhance documentation with more real-world examples as features grow.
