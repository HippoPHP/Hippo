# Hippo

[![StyleCI](https://styleci.io/repos/25982809/shield)](https://styleci.io/repos/25982809)
[![Build Status](http://img.shields.io/travis/HippoPHP/Hippo.svg?style=flat-square)](https://travis-ci.org/HippoPHP/Hippo)
[![Code Climate](http://img.shields.io/codeclimate/github/HippoPHP/Hippo.svg?style=flat-square)](https://codeclimate.com/github/HippoPHP/Hippo)
[![Test Coverage](http://img.shields.io/codeclimate/coverage/github/HippoPHP/Hippo.svg?style=flat-square)](https://codeclimate.com/github/HippoPHP/Hippo)
[![Dependencies](http://www.versioneye.com/user/projects/545de609eb8df2d3b4000051/badge.svg?style=flat-square)](http://www.versioneye.com/user/projects/545de609eb8df2d3b4000051)

## Installation & Usage

Installing and using Hippo is really straightforward with [Composer](https://getcomposer.org).

### Installation

To start using Hippo in your project add the following to `composer.json`:

```json
"require": {
    "hippophp/hippo": "~1.0@dev"
}
```

If you want to develop Hippo, you'll need [Git](http://git-scm.org) and >= PHP 5.4 installed on your system.

Clone the repo to your local environment:

```bash
$ git clone git@github.com:hippophp/hippo.git
```

Then install the dependencies:

```bash
$ cd hippo
$ composer install
```

### Usage

Once Hippo is in a directory you can run it with:

```bash
$ ./bin/hippo ./src
```

By default Hippo will output everything to `STDOUT`. You can get help information with:

```bash
$ ./bin/hippo --help
Hippo 0.1.0 by James Brooks, Marcin Kurczewski

Usage: hippo [switches] <directory>
  -h, --help                Prints this usage information
  -v, --version             Print version information
  -l, --log LOGLEVELS       Sets which severity levels should be logged
                            (default: "info,warning,error")
  -s, --strict 1|0          Enables or disables strict mode (default: 0)
                            Strict mode will exit with code 1 on any violation.
  -q, --quiet 1|0           Same as --log ""
      --verbose 1|0         Same as --log "info,warning,error"
  -c, --config PATH         Use specific config (default: "base")
  --report-xml PATH         Output a Checkstyle-compatible XML to PATH

Available configs:
  - base
  - PEAR
  - PGS-2
  - PSR-1
  - PSR-2
```

## Tests

We've built a test system against Hippo. Tests are ran on Travis CI for every pull request which is made.

## About

Hippo originally started life as a fork of [PHPCheckstyle](https://github.com/phpcheckstyle/phpcheckstyle), however after realising that a complete rewrite would be needed, the core ideas changed and as such, Hippo was born.

Hippo is an open-source tool that helps PHP programmers adhere to certain coding conventions. The tools checks the input PHP source code and reports any violations against the given standards.

Compatible with PHP 5.4 and up.

## Goals

- [x] Create a proper test suite.
- [x] Checks should be able to implement different kind of check types, giving more freedom on a per-check basis.
- [x] Integrate PHP-Parser for AST. Checks can extend some kind of AST Node Tree class.
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
