---
title: Home
description: Sons of PHP has many libraries and projects for developers.
cover: .gitbook/assets/top-rocker.png
coverY: 0
layout:
  cover:
    visible: true
    size: full
  title:
    visible: true
  description:
    visible: true
  tableOfContents:
    visible: true
  outline:
    visible: true
  pagination:
    visible: true
---

# 🏠 Home

## Welcome to the Sons of PHP

### Reporting Issues

Please use the main [Sons of PHP Repository](https://github.com/SonsOfPHP/sonsofphp) to report any [issues](https://github.com/SonsOfPHP/sonsofphp/issues) that you find.

### Contributing

Please submit [Pull Requests](https://github.com/SonsOfPHP/sonsofphp/pulls) to the main [Sons of PHP Repository](https://github.com/SonsOfPHP/sonsofphp).

### Getting Help

Please visit the [Sons of PHP Organization Discussions](https://github.com/orgs/SonsOfPHP/discussions) section to ask questions and get more help.

# About Sons of PHP

Sons of PHP is a collection of various PHP projects that are split into
"Contracts" and "Components". After that, functionality is extended by using
"Bridges". Beyond that are plugins, bundles, and packages that are specifically
for various frameworks.

The main focus of Sons of PHP is small re-usable components.

## Contracts

Everything starts with contracts. The contracts are NOT meant to replace PSR
standards. Contracts are where all the interfaces are stored. There is no
executable code here. Contracts are a way to provide other developers the
ability to create components using a standard interface.

Contracts may also extend PSR standards as well as core PHP interfaces.

Contracts are also meant to be standalone. They do not require any other
contracts other than PSRs.

## Components

All components will use the contracts to implement the code. These are the main
packages that you will use in your projects.

Components are also meant to be standalone. Generally, there will be no
additional requirements. Some components may require other additional Sons of
PHP components, but for the most part, additional functionality is added by
Bridges.

## Bridges

A bridge will connect a Sons of PHP component to another library. For example,
the Pager component has additional packages that use Doctrine. The Filesystem
component also has an AWS S3 bridge that allows you to use AWS S3 buckets to
store files.

## Bundles, Plugins, etc.

Some frameworks out there (Symfony, Laravel, etc.) allow developers to install
bundles, plugins, etc. Sons of PHP has a few of these and the leverage the
various components and bridges.

## Additional Standalone Projects

Sons of PHP also has standalone projects such as Bard. These projects are
include many different Sons of PHP components and bridges, but also include
other libraries.
