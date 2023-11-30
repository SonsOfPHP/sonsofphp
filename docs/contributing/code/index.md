---
title: Contributing Code
---

Always looking for new code contributions. Just fork the `sonsofphp/sonsofphp`
repo, cut a branch and add your code. Once you push the feature branch up,
create a PR it.

You can take a look at the existing issues, or add new code yourself.

# Git Branch Workflow

* Mainline = This is the `main` branch.
* Version branches = branch `4.x` is for the Sons of PHP latest 4.x code

# New Features

New Features are only accepted for the mainline.

Cut off of the mainline (main) and submit a PR to have it merged back into the
mainline (main).

# Bug Fixes

Bug fixes are accepted mainline and previous version only. If the current
version is 5, only bug fixes for `main` and `4.x` branches are accepted.

Cut branch off the version and submit a PR to have it merged into that version
branch.

# Security Fixes

Security fixes are accepted on the mainline, previous 2 version branches. If the
current version is 5, security fixes can be submitted for `main`, `4.x`, and
`3.x` branches.

Cut branch off the version and submit a PR to have it merged into that version
branch.
