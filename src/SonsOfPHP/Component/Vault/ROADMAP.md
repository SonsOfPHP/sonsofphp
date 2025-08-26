# Vault Component Roadmap

## Upcoming Features

### Add filesystem storage adapter
- [ ] Implementation uses encrypted files to persist secrets.
- **Acceptance Criteria**
  - All Global DoR items are satisfied before implementation begins.
  - Filesystem adapter securely reads and writes secrets.
  - Implementation meets the Global DoD.

### Add database storage adapter
- [ ] Store secrets in a relational database using PDO.
- **Acceptance Criteria**
  - All Global DoR items are satisfied before implementation begins.
  - Secrets are encrypted before storage.
  - Implementation meets the Global DoD.

### Add Redis storage adapter
- [ ] Store secrets in Redis for fast access.
- **Acceptance Criteria**
  - All Global DoR items are satisfied before implementation begins.
  - Redis adapter encrypts secrets and handles expirations.
  - Implementation meets the Global DoD.

### Add secret versioning
- [ ] Provide APIs to maintain secret history across updates.
- **Acceptance Criteria**
  - All Global DoR items are satisfied before implementation begins.
  - Previous versions of a secret remain accessible.
  - Implementation meets the Global DoD.

### Provide CLI tools for managing secrets
- [ ] Expose commands to set, get, and rotate secrets.
- **Acceptance Criteria**
  - All Global DoR items are satisfied before implementation begins.
  - CLI allows basic secret management tasks.
  - Implementation meets the Global DoD.
