# Changelog
All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v1.5.0] - 2020-08-01
### Added
- Option to generate feed based on variants instead of products
- Changelog
### Changed
- Set minimum package requirement for `spatie/array-to-xml` to `2.10`
### Removed
- Empty fields from Sooqr feed

## [v1.4.3] - 2020-07-26
### Changed
- License and readme
### Removed
- Removed XML formatting in production

## [v1.4.2] - 2020-07-26
### Added
- Missing CDATA markup for more fields
### Changed
- Fix output for boolean value of `is_featured`-field
- Updated variant fields for Sooqr feed

## [v1.4.1] - 2020-07-26
### Changed
- Fixed issue when using `HasCategoryTreeStructure` without `HasCategoryInfo`
- Updated namespace for feeds

## [v1.4.0] - 2020-07-26
### Added
- Feed template for Sooqr
- Github actions configuration
- PHPUnit test configuration

## [v1.3.0] - 2020-07-10
### Added
- Specification to feed

## [v1.2.2] - 2020-07-06
### Changed
- Fixed issue booting package before migrations

## [v1.2.1] - 2020-07-06
### Added
- Missing down-statement for table migration
### Removed
- if-statement for booting migrations

## [v1.2.0] - 2020-07-05
### Changed
- Feed list command output
- The configuration of existing feeds 

## [v1.1.0] - 2020-07-05
### Added
- Option to overwrite default mapper class
- Validation to `ecom-feed:create` questions
### Changed
- Moved kernel scheduler and database migrations
- Converted migration file to generate from a stub
- Composer package requirements

## [v1.0.3] - 2020-07-04
### Changed
- Command class names
- Updated `ecom-feed:create`-command to output public URL after creation

## [v1.0.2] - 2020-06-28
### Changed
- Fixed `ecom-feed:list` output when no lists exist

## [v1.0.1] - 2020-06-28
### Changed
- Packagist links in README.md

## [v1.0.0] - 2020-06-28
### Added
- Commands to create, list and remove product feed via `artisan`-commands
- Option to overwrite XML data structure via interface binding

[Unreleased]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.5.0...HEAD
[v1.5.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.4.3...v1.5.0
[v1.4.3]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.4.2...v1.4.3
[v1.4.2]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.4.1...v1.4.2
[v1.4.1]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.4.0...v1.4.1
[v1.4.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.3.0...v1.4.0
[v1.3.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.2.2...v1.3.0
[v1.2.2]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.2.1...v1.2.2
[v1.2.1]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.2.0...v1.2.1
[v1.2.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.0.3...v1.1.0
[v1.0.3]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.0.2...v1.0.3
[v1.0.2]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/timothydc/laravel-lightspeed-ecom-product-feeds/releases/tag/v1.0.0