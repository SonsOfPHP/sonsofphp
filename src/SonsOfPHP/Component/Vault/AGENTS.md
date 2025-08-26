# Agents Guide for Package: src/SonsOfPHP/Component/Vault

## Scope

- Source: `src/SonsOfPHP/Component/Vault`
- Tests: `src/SonsOfPHP/Component/Vault/Tests`
- Do not edit: any `vendor/` directories

## Workflows

- Install once at repo root: `make install`
- Test this package only: `PHPUNIT_OPTIONS='src/SonsOfPHP/Component/Vault/Tests' make test`
- Style and static analysis: `make php-cs-fixer` and `make psalm`
- Upgrade code (may modify files): `make upgrade-code`

## Testing Guidelines

- Use PHPUnit attributes such as `#[CoversClass]` for coverage.
- Each test method should verify a single behavior.

## Component Notes

- Secrets are marshalled using implementations of `MarshallerInterface`.
- Prefer domain-specific exceptions under `Exception/` when reporting errors.
