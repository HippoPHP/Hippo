# Hippo

[![Build Status](http://img.shields.io/travis/HippoPHP/Hippo.svg?style=flat-square)](https://travis-ci.org/HippoPHP/Hippo)
[![Code Climate](http://img.shields.io/codeclimate/github/HippoPHP/Hippo.svg?style=flat-square)](https://codeclimate.com/github/HippoPHP/Hippo)
[![Test Coverage](http://img.shields.io/codeclimate/coverage/github/HippoPHP/Hippo.svg?style=flat-square)](https://codeclimate.com/github/HippoPHP/Hippo)

## About

Hippo originally started life as a fork of [PHPCheckstyle](https://github.com/phpcheckstyle/phpcheckstyle), however after realising that a complete rewrite would be needed, the core ideas changed and as such, Hippo was born.

Hippo is an open-source tool that helps PHP programmers adhere to certain coding conventions. The tools checks the input PHP source code and reports any violations against the given standards.

Compatible with PHP 5.4 and up.

## Goals

- [x] Create a proper test suite.
- [ ] Checks should be able to implement different kind of check types, giving more freedom on a per-check basis.
- [ ] Integrate PHP-Parser for AST. Checks can extend some kind of AST Node Tree class.
- [ ] Set default standards to [PSR 2](http://www.php-fig.org/psr/psr-2/).
    - [ ] Standards and configurations should be able to be extended.
    - [ ] PSR-1
    - [ ] PSR-2
    - [ ] PGS-2
    - [ ] Zend
    - [ ] PEAR
- [x] Configuration keys should allow flexibility in the naming pattern, it shouldn't matter so long as it is a valid name.
    - [x] camelCase
    - [x] snake_case
    - [x] PascalCase
- [X] Run as a binary with console output and as a library.
- [x] Switch to [semver](http://semver.org) versioning.

# License
See [LICENSE](/LICENSE.txt)
