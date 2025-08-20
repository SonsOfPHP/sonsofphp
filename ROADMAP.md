# Sons of PHP Roadmap

This roadmap outlines planned libraries and updates for the Sons of PHP monorepo. Completed items must be removed to keep the list current.

## Global Definition of Ready
- Problem statement and goals are clearly documented.
- Scope, dependencies, and related packages are identified.
- Acceptance criteria and testing strategy are defined.
- Stakeholders have reviewed and approved the proposal.

## Global Definition of Done
- Implementation meets the documented acceptance criteria.
- Unit and integration tests are added or updated and pass.
- Documentation reflects the new or changed behavior.
- `make test`, `make php-cs-fixer`, and `make psalm` pass.
- The corresponding roadmap entry is removed.

## Prioritized Roadmap
1. **Stabilize Core Components for 1.0 Release**
   - Finalize public APIs for existing components.
   - Increase test coverage and fix outstanding issues.
   - Prepare changelogs and migration guides.
   - **Acceptance Criteria**
     - Every component tagged for 1.0 has >=90% test coverage.
     - Changelogs outline breaking changes and upgrade paths.
     - Documentation updated for all stabilized components.

2. **Update HTTP Libraries to Latest PSR Versions**
   - Align `HttpMessage` and `HttpFactory` components with PSR-7 v2 and PSR-17 updates.
   - Ensure compatibility with PSR-18 clients.
   - **Acceptance Criteria**
     - Components comply with PSR-7 v2 and PSR-17.
     - Integration tests against a PSR-18 client pass.
     - Docs include examples using the updated interfaces.

3. **Introduce Validation Component**
   - Provide a rule-based validation system similar to Symfony Validator but framework agnostic.
   - Include common validators and an extensible API for custom rules.
   - **Acceptance Criteria**
     - New `Component/Validation` with rule definitions and error reporting.
     - Unit tests cover each validator and failure scenario.
     - Documentation with usage examples and customization guide.

4. **Add Queue Component for Background Jobs**
   - Implement a simple queue abstraction with in-memory and Redis adapters.
   - Provide Symfony Messenger bridge for interoperability.
   - **Acceptance Criteria**
     - `Component/Queue` with enqueue/dequeue interfaces and adapters.
     - Bridge package integrates with Symfony Messenger.
     - Tests demonstrate reliable job processing and failure handling.

5. **Enhance Symfony Bridges for Symfony 7**
   - Review all Symfony bridge packages for compatibility with Symfony 7.
   - Add support for new security and HTTP features where applicable.
   - **Acceptance Criteria**
     - Bridges pass tests against Symfony 7 components.
     - Deprecated APIs removed and replacements documented.
     - Release notes describe Symfony 7 compatibility.

## Suggestions
- Automate dependency updates with a scheduled tool (e.g., Renovate or Dependabot).
- Establish coding style guides per component to encourage consistency.
- Consider setting up benchmarks for performance-critical components.
