<h1 align="center">Confusable Homoglyphs</h1>
<p align="center"><em>A PHP port of <a href="https://github.com/vhf/confusable_homoglyphs">vhf/confusable_homoglyphs</a></em></p>

<p align="center">
  <a href="https://github.com/photogabble/php-confusable-homoglyphs/actions/workflows/phpunit.yml"><img src="https://github.com/photogabble/php-confusable-homoglyphs/actions/workflows/phpunit.yml/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/photogabble/php-confusable-homoglyphs"><img src="https://img.shields.io/packagist/v/photogabble/php-confusable-homoglyphs.svg" alt="Latest Stable Version"></a>
  <a href="LICENSE"><img src="https://img.shields.io/github/license/photogabble/php-confusable-homoglyphs.svg" alt="License"></a>
</p>

## About this package

> Unicode homoglyphs can be a nuisance on the web. Your most popular client, AlaskaJazz, might be upset to be impersonated by a trickster who deliberately chose the username ΑlaskaJazz. (The A is the greek letter [capital alpha](http://www.amp-what.com/unicode/search/%CE%91))

This is a complete port of the Python library [vhf/confusable_homoglyphs](https://github.com/vhf/confusable_homoglyphs) to PHP. I found myself needing its functionality after reading [this article](https://www.b-list.org/weblog/2018/feb/11/usernames/) by James Bennett on validating usernames and how [django-registration](https://github.com/ubernostrum/django-registration/blob/1d7d0f01a24b916977016c1d66823a5e4a33f2a0/registration/validators.py) does so.

A huge thank you goes to the Python package creator [Victor Felder](https://github.com/vhf) and its contributors [Ryan Kilby](https://github.com/rpkilby) and [muusik](https://github.com/muusik); without their work this port would not exist.

This library is compatible with PHP versions 7.3 and above.

## Install

Install this library with composer: `composer require photogabble/php-confusable-homoglyphs`.

## Usage

Please see the [tests](https://github.com/photogabble/php-confusable-homoglyphs/tree/master/tests) for detailed example of usage.

#### Known Usage

* [Laravel Registration Validator package ](https://github.com/photogabble/laravel-registration-validator)
* If you use this package in your open source project please create a pull request to add a link here

## Is the data up to date?

This project currently ships with unicode consortium public data version 10.0.0.

The unicode blocks aliases and names for each character are extracted from [this file](http://www.unicode.org/Public/UNIDATA/Scripts.txt) provided by the unicode consortium.

The matrix of which character can be confused with which other characters is built using [this file](http://www.unicode.org/Public/security/latest/confusables.txt) provided by the unicode consortium.

The version this project currently ships with was generated on the 17th Feb 2022.
