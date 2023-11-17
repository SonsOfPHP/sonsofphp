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
        "sonsofphp/common-contract-implementation": "^1.0"
    },
```

## Method Naming

Functionality around method names are kept as similar as possible. They SHOULD
follow this convention.

### with*

`with*` methods are meant to be used on value objects. They will return a new
object with the value(s) modified.

Examples:
- `withFirstName`

### has*

Will always return a `boolean` value. Used to check if a property or value
exists on the object. Doesn't matter the type of the value.

Examples
- `hasFirstName`

### is*

Will always return a `boolean` value.

Examples:
- `isNew`
- `isDeleted`

### get*

Used to return property values.

Examples:
- `getFirstName`
- `getLastName`

### set*

Used to set property values. SHOULD always return the same instance of the
object to allow chaining.

Examples:
- `setFirstName`
- `setLastName`

### to*

Used to convert an object to a specific format.

Examples:
- `toJson`
- `toString`
- `toArray`
- `toInteger`
- `toFloat`
- `toBoolean`
