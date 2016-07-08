pop-version
===========

[![Build Status](https://travis-ci.org/popphp/pop-version.svg?branch=master)](https://travis-ci.org/popphp/pop-version)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-version)](http://cc.popphp.org/pop-version/)

OVERVIEW
--------
`pop-version` is a simple component to determine and compare the latest version of Pop PHP,
as well as evaluate the system environment and dependencies.

`pop-version` is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-version` using Composer.

    composer require popphp/pop-version

BASIC USAGE
-----------

### Check the version

```php
// echo '2.0.0'
echo Pop\Version\Version::VERSION;

// echo '2.0.0'
echo Pop\Version\Version::getLatest();

// Returns true
if (Pop\Version\Version::isLatest()) { }
```

### Evaluate the system environment

```php
$env = Pop\Version\Version::systemCheck();
```

That will return an array will values like this:

    Array
    (
        [pop] => Array
            (
                [installed] => 2.0.0
                [latest] => 2.0.0
                [compare] => 0
            )

        [php] => Array
            (
                [installed] => 5.4.32
                [required] => 5.4.0
                [compare] => 1
            )

        [windows] =>
        [environment] => Array
            (
                [apc] => 1
                [archive] => Array
                    (
                        [tar] =>
                        [zip] => 1
                        [bz2] => 1
                        [zlib] => 1
                    )

                [curl] => 1
                [db] => Array
                    (
                        [mysqli] => 1
                        [oracle] =>
                        [pdo] => Array
                            (
                                [mysql] => 1
                                [pgsql] => 1
                                [sqlite] => 1
                                [sqlsrv] =>
                            )

                        [pgsql] => 1
                        [sqlite] => 1
                        [sqlsrv] =>
                    )

                [dom] => Array
                    (
                        [dom_document] => 1
                        [simple_xml] => 1
                    )

                [ftp] => 1
                [image] => Array
                    (
                        [gd] => 1
                        [gmagick] =>
                        [imagick] => 1
                    )

                [ldap] => 1
                [mcrypt] => 1
                [memcache] => 1
                [redis] => 1
                [soap] => 1
                [yaml] => 1
            )

    )

