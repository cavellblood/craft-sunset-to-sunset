# Sunset To Sunset Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.1.1 - 2019-11-18
### Changed
- The full message container now has `overflow-y` set to `scroll` allowing custom templates to scroll content if content is greater than the vertical screen height.

### Fixed
- The full message template no longer loads control panel CSS which could break styles on the front-end.

## 2.1.0 - 2019-11-12
### Added
- Users can now choose a template of their own to load during the Sabbath hours.

## 2.0.4 - 2019-06-23
### Added
- The full message template now has a heading.

### Changed
- Changed re-opening paragraph in full message template to be more generic.

## 2.0.3 - 2019-06-23
### Changed
- Updated plugin link url in control panel settings template.

## 2.0.2 - 2019-06-23
### Changed
- Disable scrolling on `html` when full message template is showing.
- Increase `z-index` value on banner and full message templates to keep it on top.
- Remove references to redirect template since we are using a new method to render the templates.

## 2.0.1 - 2019-06-23
### Changed
- Fixed banner class names to match what was in the stylesheet.

## 2.0.0 - 2019-06-20
### Added
- Initial release
