# Hippo

[![Build Status](https://travis-ci.org/HippoPHP/Hippo.svg)](https://travis-ci.org/HippoPHP/Hippo)
[![Coverage Status](https://coveralls.io/repos/HippoPHP/Hippo/badge.png)](https://coveralls.io/r/HippoPHP/Hippo)
[![Gitter chat](https://badges.gitter.im/hippophp.png)](https://gitter.im/hippophp)

**You're looking at the development branch. See the [Goals](#goals) below to see what is different in this branch.**

## About

Hippo originally started life as a fork of [PHPCheckstyle](https://github.com/phpcheckstyle/phpcheckstyle), however due to the complete rewrite, we decided to start a new project.

Hippo is an open-source tool that helps PHP programmers adhere to certain coding conventions. The tools checks the input PHP source code and reports any violations against the given standards.

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
- [ ] Configuration keys should allow flexibility in the naming pattern, it shouldn't matter so long as it is a valid name.
    - [ ] camelCase
    - [ ] snake_case
    - [ ] PascalCase
- [ ] Run as a binary with console output and as a library.
- [ ] Switch to [semver](http://semver.org) versioning.

# License
See [LICENSE](/LICENSE.txt)
