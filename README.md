# MailGrab

Catch-all SMTP server for local debugging purposes.

This SMTP server catches all e-mail being sent through it and provides an interface to inspect the e-mails.

*Note: this SMTP server is meant to be run locally. As such several security considerations (e.g. SMTP transaction delays) has been omitted by design. Never run this project as a public service.*

## Project status

Currently just a PoC. All things will change.

Requirements

- PHP 7.2

## Usage

- Start the server by running `vendor/bin/aerys -c bin/web-server.php -d`
- Point your webbrowser to `http://localhost:8000`
- Send mails to the service by running one of the example, e.g.: `php examples/full-test.php`, `php examples/native.php`, `php examples/swiftmailer.php`
- Profit!

*Note: as this project is still in pre-alpha all of the above will change.*
