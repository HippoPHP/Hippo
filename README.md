# PHPCheckstyle

[![Build Status](https://travis-ci.org/PHPCheckstyle/phpcheckstyle.svg?branch=ast-parser)](https://travis-ci.org/PHPCheckstyle/phpcheckstyle)
[![Coverage Status](https://coveralls.io/repos/PHPCheckstyle/phpcheckstyle/badge.png?branch=ast-parser)](https://coveralls.io/r/PHPCheckstyle/phpcheckstyle?branch=ast-parser)
[![Gitter chat](https://badges.gitter.im/phpcheckstyle.png)](https://gitter.im/phpcheckstyle)

**You're looking at the development branch. See the [Goals](#goals) below to see what is different in this branch.**

## About

PHPCheckstyle is an open-source tool that helps PHP programmers adhere to certain coding conventions. The tools checks the input PHP source code and reports any violations against the given standards.

This project was originally written by Hari Kodungallur and Nimish Pachapurkar and is now maintained by Benoit Pesty, James Brooks and Marcin Kurczewski.

## Goals

PHPCheckstyle has been around since 2011 and in that time there has been changes in PHP and new packages developed that allow us to rewrite PHPCheckstyle with a lot of cool new features.

This branch isn't just new changes, it's a complete, from the ground up rewrite.

- [x] Create a proper test suite. [#18](https://github.com/PHPCheckstyle/phpcheckstyle/issues/18)
- [ ] Integrate PHP-Parser for AST. Checks can extend some kind of AST Node Tree class.
- [ ] Set default standards to [PSR 2](http://www.php-fig.org/psr/psr-2/), config should be able to override. [#43](https://github.com/PHPCheckstyle/phpcheckstyle/issues/43)
- [ ] Run as a binary with console output and as a library. [#46](https://github.com/PHPCheckstyle/phpcheckstyle/issues/46)
- [ ] Switch to [semver](http://semver.org) versioning. [#42](https://github.com/PHPCheckstyle/phpcheckstyle/issues/42)

# License
See [LICENSE](/LICENSE.txt)
