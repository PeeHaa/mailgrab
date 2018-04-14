# MailGrab

[![Latest Stable Version](https://poser.pugx.org/peehaa/mailgrab/v/stable)](https://packagist.org/packages/peehaa/mailgrab)
[![Build Status](https://travis-ci.org/PeeHaa/mailgrab.svg?branch=v0.1.0)](https://travis-ci.org/PeeHaa/mailgrab)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PeeHaa/mailgrab/badges/quality-score.png?b=v0.1.0)](https://scrutinizer-ci.com/g/PeeHaa/mailgrab/?branch=v0.1.0)
[![Code Coverage](https://scrutinizer-ci.com/g/PeeHaa/mailgrab/badges/coverage.png?b=v0.1.0)](https://scrutinizer-ci.com/g/PeeHaa/mailgrab/?branch=v0.1.0)
[![License](https://poser.pugx.org/peehaa/mailgrab/license)](https://packagist.org/packages/peehaa/mailgrab)

Catch-all SMTP server for local debugging purposes.

This SMTP server catches all e-mail being sent through it and provides an interface to inspect the e-mails.

*Note: this SMTP server is meant to be run locally. As such several security considerations (e.g. SMTP transaction delays) has been omitted by design. Never run this project as a public service.*

![Screenshot MailGrab](https://i.imgur.com/E9qA1sK.png "Screenshot")

## Project status

This branch (v0.1.0) is in a functioning state. Working on stabilizing the codebase.

Once missing tests are added and I've added functionality that I feel is mandatory I will officially tag a v0.1.0 release based on this branch.

Requirements

- PHP 7.1

## Installation

### Composer

    composer create-project peehaa/mailgrab

### Phar

Download the latest phar file from the releases page (coming soon) and make the file executable.

## Usage

### Composer

`./bin/mailgrab` will start MailGrab using the default configuration:

- HTTP port: 9000
- SMTP port: 9025

*See `./bin/mailgrab --help` for more configuration options*

### Phar

`/path/to/mailgrab.phar` will start MailGrab using the default configuration:

- HTTP port: 9000
- SMTP port: 9025

*See `./bin/mailgrab --help` for more configuration options*

Once the MailGrab server is started you can point your browser to `http://localhost:9000` to access the webinterface.  
If you send a mail to the server over port 9025 it will automatically be displayed in the webinterface.  
There are example mail scripts available under `./examples` (e.g. `php examples/full-test.php`) which you can run to test the functionality.

## Build and development

Currently all active development has to be based off the v0.1.0 branch.

If you want to build a phar you can run the build script located at `./bin/build` which will created a new build in the `./build` directory.
