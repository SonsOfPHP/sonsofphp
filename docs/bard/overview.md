---
title: Bard
---

Bard is used to manage monorepos.

## Installing

Bard is meant to be installed into a "tools" folder for use with your project.

```shell
mkdir tools/bard
composer require --working-dir=tools/bard sonsofphp/bard
echo "composer.lock\nvendor/" > tools/bard/.gitignore
php tools/bard/vendor/bin/bard
```

## Usage

Initialize a new bard.json file for new monorepos.

```shell
bard init
```

### Adding Repositories

```shell
bard add path/to/code repoUrl
```

### Push changes to read-only repos

```shell
bard push
```

### Create a release

```shell
bard release major
bard release minor
bard release patch
```

Bard will track the versions so you can just use the keywords: major, minor,
patch.

### Copy files

Copy the LICENSE file from the root to all packages
```shell
bard copy LICENSE
```

### Merging `composer.json` files

When you have to maintain the composer.json files, this command will take the
packages and merge those into the main composer.json file. It will also update
the package's composer.json file with the correct values as well.

```shell
bard merge
```
