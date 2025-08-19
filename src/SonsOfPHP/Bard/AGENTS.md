# Agents Guide for Package: src/SonsOfPHP/Bard

## Scope

- Source: `src/SonsOfPHP/Bard/src`
- Tests: `src/SonsOfPHP/Bard/Tests`
- Do not edit: `src/SonsOfPHP/Bard/vendor/`, `src/SonsOfPHP/Bard/dist/`

## Workflows

- Install once at repo root: `make install`
- Test this package only: `PHPUNIT_OPTIONS='src/SonsOfPHP/Bard/Tests' make test`
- Style and static analysis: `make php-cs-fixer` and `make psalm`
- Upgrade code (may modify files): `make upgrade-code`

## Notes

- Keep CLI outputs stable; update docs in `docs/bard/` if UX changes.
- Avoid committing built artifacts under `dist/`.

