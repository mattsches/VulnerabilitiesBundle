Vulnerabilities Bundle
======================

Calls the [SensioLabs Security Advisories Checker](https://security.sensiolabs.org/) and checks for known vulnerabilities.

[![Build Status](https://travis-ci.org/mattsches/VulnerabilitiesBundle.png?branch=master)](https://travis-ci.org/mattsches/VulnerabilitiesBundle)

Installation
------------

Suggested installation method is through [composer](http://getcomposer.org/):

```php
php composer.phar require mattsches/vulnerabilities-bundle:dev-master
```

Setup
-----

Add the following to your `app/config/config_dev.yml` (you only want to use this in the dev environment)

```yml
mattsches_vulnerabilities:
    filesystem_cache_path: "%kernel.cache_dir%/vulnerabilities"
    filesystem_cache_ttl: 604800
```
