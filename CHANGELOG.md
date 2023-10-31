# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

To get the diff for a specific change, go to https://github.com/SonsOfPHP/sonsofphp/commit/XXX where XXX is the change hash
To get the diff between two versions, go to https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.4...v0.3.5

<!--
Please Use (and keep them organized in this order as well):
 - Added for new features.
 - Changed for changes in existing functionality.
 - Deprecated for soon-to-be removed features.
 - Removed for now removed features.
 - Fixed for any bug fixes.
 - Security in case of vulnerabilities.

Examples:
* [PR #69](https://github.com/SonsOfPHP/sonsofphp/pull/69) Added new feature
-->

## [Unreleased]

* [PR #51](https://github.com/SonsOfPHP/sonsofphp/pull/51) Added new Filesystem component
* [PR #59](https://github.com/SonsOfPHP/sonsofphp/pull/59) Added new HttpMessage component

## [0.3.8]

Forgot to add stuff ;p

## [0.3.7]

* [event-sourcing]
  * Deprecated `::new` for Aggregates
  * Updated code to remove deprecations from libraries
* [event-sourcing-symfony]
  * Removing deprecated files

## [0.3.6]

* Changed min PHP version to 8.1 for all packages
* [EventSourcing] Added MessagePayload and MessageMetadata classes
* [EventSourcing] Deprecated classes in Symfony Bridge to better organize the library
* [CQRS] Adding Symfony Bundle

[Unreleased]: https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.8...HEAD
[0.3.8]: https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.8...v0.3.9
[0.3.7]: https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.7...v0.3.8
[0.3.6]: https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.6...v0.3.7
[0.3.5]: https://github.com/SonsOfPHP/sonsofphp/compare/v0.3.5...v0.3.6
