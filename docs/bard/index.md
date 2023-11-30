---
title: Bard
---

# Bard

Bard is used to manage monorepos.

## Usage

Initialize a new bard.json file

```shell
bard init
```

Add repositories

```shell
bard add path/to/code repoUrl
```

Push changes to read-only repos

```shell
bard push
```

Create a release

```shell
bard release major
bard release minor
bard release patch
```

Bard will track the versions so you can just use the keywords: major, minor,
patch.

Copy the LICENSE file from the root to all packages
```shell
bard copy LICENSE
```
