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

## How to Use This Roadmap

- Each entry below is an **epic** with a name and short description.
- Epics contain a list of tasks. Every task must:
  - satisfy the [Global Definition of Ready](#global-definition-of-ready) before work begins;
  - meet the [Global Definition of Done](#global-definition-of-done) on completion; and
  - define its own acceptance criteria.
- Remove tasks as they are completed.
- When an epic has no remaining tasks, remove the epic.

## Prioritized Roadmap

### Epic: Stabilize Core Components for 1.0 Release
**Description:** Finalize public APIs, increase test coverage, and prepare documentation for a stable 1.0 release.

- [ ] Finalize public APIs for existing components.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Public APIs are documented and approved.
    - Implementation meets the Global DoD.
- [ ] Increase test coverage and fix outstanding issues.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Components tagged for 1.0 reach at least 90% test coverage.
    - All known issues are resolved.
    - Implementation meets the Global DoD.
- [ ] Prepare changelogs and migration guides.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Changelogs outline breaking changes and upgrade paths.
    - Migration guides are published.
    - Implementation meets the Global DoD.

### Epic: Update HTTP Libraries to Latest PSR Versions
**Description:** Align HTTP components with the latest PSR standards and ensure client compatibility.

- [ ] Align `HttpMessage` and `HttpFactory` with PSR-7 v2 and PSR-17.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Components comply with PSR-7 v2 and PSR-17.
    - Implementation meets the Global DoD.
- [ ] Ensure compatibility with PSR-18 clients.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Integration tests against a PSR-18 client pass.
    - Implementation meets the Global DoD.
- [ ] Update documentation with examples for the updated interfaces.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Docs include examples using the updated interfaces.
    - Implementation meets the Global DoD.

### Epic: Introduce Validation Component
**Description:** Provide a framework-agnostic, rule-based validation system with extensible APIs.

- [ ] Implement `Component/Validation` with rule definitions and error reporting.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Component exposes an extensible API for custom rules.
    - Implementation meets the Global DoD.
- [ ] Add unit tests covering each validator and failure scenario.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Tests cover common validators and edge cases.
    - Implementation meets the Global DoD.
- [ ] Document usage examples and customization guides.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Documentation explains basic usage and extension points.
    - Implementation meets the Global DoD.

### Epic: Add Queue Component for Background Jobs
**Description:** Implement a queue abstraction with adapters and a Symfony Messenger bridge.

- [ ] Implement queue abstraction with in-memory and Redis adapters.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - `Component/Queue` provides enqueue/dequeue interfaces and adapters.
    - Tests demonstrate reliable job processing and failure handling.
    - Implementation meets the Global DoD.
- [ ] Provide Symfony Messenger bridge for interoperability.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Bridge integrates with Symfony Messenger.
    - Implementation meets the Global DoD.

### Epic: Enhance Symfony Bridges for Symfony 7
**Description:** Review bridge packages for Symfony 7 compatibility and add support for new features.

- [ ] Review and update bridge packages for Symfony 7 compatibility.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Bridges pass tests against Symfony 7 components.
    - Implementation meets the Global DoD.
- [ ] Remove deprecated APIs and document replacements.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Deprecated APIs are removed.
    - Docs describe new APIs and migration paths.
    - Implementation meets the Global DoD.
- [ ] Document release notes describing Symfony 7 compatibility.
  - **Acceptance Criteria**
    - All Global DoR items are satisfied before implementation begins.
    - Release notes highlight Symfony 7 support.
    - Implementation meets the Global DoD.

## Suggestions

- Automate dependency updates with a scheduled tool (e.g., Renovate or Dependabot).
- Establish coding style guides per component to encourage consistency.
- Consider setting up benchmarks for performance-critical components.
- Link epics and tasks to issue trackers for traceability.
- Review roadmap items quarterly to keep priorities current.
