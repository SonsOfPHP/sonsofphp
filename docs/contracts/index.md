---
title: Contracts
---

# Contracts

Contracts are interfaces that can be reusable across multiple different
libraries and projects. The actual implementation of the interfaces is left up
to you.

The interfaces also enhance existing PSRs.

Whenever possible, the components and projects created by Sons of PHP will
implement these interfaces.

## Implementation

If you want to provide a concrete library for others to use, add this to your
`composer.json` file.

```json
    "provide": {
        "sonsofphp/core-contract-implementation": "^1.0"
    },
```
