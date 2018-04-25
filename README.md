# MailGrab

[![Latest Stable Version](https://poser.pugx.org/peehaa/mailgrab/v/stable)](https://packagist.org/packages/peehaa/mailgrab)
[![Build Status](https://travis-ci.org/PeeHaa/mailgrab.svg?branch=master)](https://travis-ci.org/PeeHaa/mailgrab)
[![Build status](https://ci.appveyor.com/api/projects/status/0vg23vb7ohyuxjqr/branch/master?svg=true)](https://ci.appveyor.com/project/PeeHaa/mailgrab/branch/master)
[![Coverage Status](https://coveralls.io/repos/github/PeeHaa/mailgrab/badge.svg?branch=master)](https://coveralls.io/github/PeeHaa/mailgrab?branch=master)
[![License](https://poser.pugx.org/peehaa/mailgrab/license)](https://packagist.org/packages/peehaa/mailgrab)

Catch-all SMTP server for local debugging purposes.

This SMTP server catches all e-mail being sent through it and provides an interface to inspect the e-mails.

*Note: this SMTP server is meant to be run locally. As such several security considerations (e.g. SMTP transaction delays) have been omitted by design. Never run this project as a public service.*

![Screenshot MailGrab](https://i.imgur.com/E9qA1sK.png "Screenshot")

## Project status

This project is currently working towards a first stable release version.  
The master branch of this project will always be in a functioning state and will always point to the last release.

All active development should be based off the v0.4.0 branch.

### Current limitations

- Currently the project only supports unauthenticated smtp requests (without `AUTH` command)
- No persistent storage
- Because we currently only support in-memory storage the project may run out of memory when handling a lot of mails or mails with a lot attachments

## Requirements

- PHP 7.1

## Installation

### Composer

    composer create-project peehaa/mailgrab

### Phar

Download the latest phar file from the [releases](https://github.com/PeeHaa/mailgrab/releases) page.

## Usage

### Composer

`./bin/mailgrab` will start MailGrab using the default configuration:

- HTTP port: 9000
- SMTP port: 9025

*See `./bin/mailgrab --help` for more configuration options*

Once the MailGrab server is started you can point your browser to `http://localhost:9000` to access the webinterface.  
If you send a mail to the server over port 9025 it will automatically be displayed in the webinterface.  
There are example mail scripts available under `./examples` (e.g. `php examples/full-test.php`) which you can run to test the functionality.

### Phar

`/path/to/mailgrab.phar` will start MailGrab using the default configuration:

- HTTP port: 9000
- SMTP port: 9025

*See `/path/to/mailgrab.phar --help` for more configuration options*

## Build and development

### NPM

To get started run `npm install`.

An NPM build script is provided and can be used by running `npm run build` in the project root.

### Building phars

Currently all active development has to be based off the v0.4.0 branch.

If you want to build a phar you can run the build script located at `./bin/build` which will create a new build in the `./build` directory.
