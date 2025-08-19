# Agents Guide for Package: src/SonsOfPHP/Bridge/Aws/Filesystem

## Scope

- Source: `src/SonsOfPHP/Bridge/Aws/Filesystem/src`
- Tests: `src/SonsOfPHP/Bridge/Aws/Filesystem/Tests`
- Do not edit: any `vendor/` directories

## Workflows

- Install once at repo root: `make install`
- Test this package only: `PHPUNIT_OPTIONS='src/SonsOfPHP/Bridge/Aws/Filesystem/Tests' make test`
- Style and static analysis: `make php-cs-fixer` and `make psalm`
- Upgrade code (may modify files): `make upgrade-code`

## Notes

- Ensure AWS-related integrations remain optional and well-typed.
- If user-facing behavior changes, update related docs under `docs/components/` or integration sections as applicable.

