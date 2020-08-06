<h1>
  laraSec for
  <img alt="Laravel" src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="200">
</h1>

<p>
  <a href="https://travis-ci.com/github/xqus/larasec"><img src="https://travis-ci.com/xqus/larasec.svg?branch=master" alt="Build Status"></a>
  <a href="https://packagist.org/packages/xqus/larasec"><img src="https://poser.pugx.org/xqus/larasec/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/xqus/larasec"><img src="https://poser.pugx.org/xqus/larasec/v/stable.svg" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/xqus/larasec"><img src="https://poser.pugx.org/xqus/larasec/license.svg" alt="License"></a>
</p>

## Introduction

laraSec is a Laravel package that will scan your composer dependencies
and alerts you about potention security vulnerabilities. laraSec uses
[PHP Security Advisories Database](https://github.com/FriendsOfPHP/security-advisories) as a source of known
vulnerabilities, and Packagist to check for updates.

## Installation

`composer require xqus/larasec`

If you want to set up e-mail notifications first publish the configuration-file to your config directory.

`php artisan vendor:publish --provider="xqus\laraSec\laraSecServiceProvider" --tag="config"`

Then edit the e-mail address in `config\larasec.php`.

## Usage
```
// Scan for vulnerable packages
php artisan larasec:scan

// Scan but don't update the database first
php artisan larasec:scan --update no

// Scan, and update the database without asking first
php artisan larasec:scan --update yes

// Update the database
php artisan larasec:update
```
