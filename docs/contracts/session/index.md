---
title: PHP Session Contract
---

## Installation

```shell
composer require sonsofphp/session-contract
```

## Definitions

- **Session** - Session has a name and id that are used with a cookie.
- **Attribute** - A session can have zero or more attributes. Attributes are
  stored using the session storage. Attributes will always be key/value.
- **Session Storage** - The session storage is what is used to store attributes
  of that session.
