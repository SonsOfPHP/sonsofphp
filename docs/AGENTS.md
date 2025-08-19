# Agents Guide for Docs

This project’s documentation lives in `docs/` and is organized for GitBook consumption with a table of contents in `SUMMARY.md`.

## Principles

- Keep docs accurate, concise, and task-oriented.
- Prefer small, incremental updates alongside code changes.
- Mirror package names and concepts used in code for easy navigation.

## Where to Edit

- Components: `docs/components/*.md`
- Contracts: `docs/contracts/*.md`
- Symfony bundles: `docs/symfony-bundles/*.md`
- Bard CLI: `docs/bard/*.md`
- General/Project: `docs/README.md`, `docs/getting-help.md`, etc.

## Table of Contents

- When adding new pages, update `docs/SUMMARY.md` to include them in the navigation.

## Writing Style

- Use clear headings and short sections.
- Include small code examples (PHP) when helpful.
- Document public APIs and noteworthy behavior changes.
- Link to related pages within `docs/` when context helps.

## Quick Checks

- Cross-check names, namespaces, and examples with the code.
- Keep examples runnable where possible.
- Avoid duplicating content that can be linked.

