---
title: Bard Commands
---

All commands have usage examples along with help documentation in the commands.
To view more details, run `bard <command> -h`.

## init

Initialize a new mono repository.

```shell
bard init
```

## add

Add a new package to bard.

```sh
bard add <path> <repository>
```

## merge

This command will merge all packages into the root `composer.json` file.

```sh
bard merge [<package>]
```

## push

Pushes code to their respective repositories.

```sh
bard push [<package>]
```

## release

Creates a new release. This command will update all the files that need to be updated, tag, and push the release out.

```sh
bard release <release>
```

{% hint style="success" %}
You can use `major`, `minor`, or `patch` as the `<release>` argument instead of specifying the exact version you want to release.
{% endhint %}
