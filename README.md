# Konstruktor App Project

An Konstruktor CakePHP based app for use with composer

## Developing workflow

There is 3 main branches used in this project:

- develop: this is branch for completely new features and recommended to use for new features.
- staging_branch: Pre-release branch. Some team members who working with frontend bugs or small changes must create own branches for tasks and merge changes back to this branch. Recommended for bugfixes.
- master: Production branch. Do not commit your changes in this branch without any special requirements!!!

## Requirements

PHP 5.4 and above, but recommended version is 5.5.29

## Installation

### Build

Make changes in your php.ini and enable `short_open_tag = On`

	First run: composer install [-vvv]
	Next runs: composer update [-vvv]

`-vvv` enable verbosing to console output for build process.

This will build app project, with dependencies, based on this repository. Be sure to point
the webserver at the `webroot` folder and ensure that [URL rewriting][1]
is configured correctly.

`composer update` recommended to run after each update from main repo.

### App configure

Create MySQL database in your dev environment and copy the file `app/Config/.env.default` to `app/Config/.env` and edit it.

Import `app/Config/Schema/dev-fake-db.sql` to your newly created database.

Mostly you need to edit database configurations in copied .env file

This template is setup to configure the application via [environment variables](http://en.wikipedia.org/wiki/Environment_variable) and [data source names](http://en.wikipedia.org/wiki/Data_source_name) (DSN).

### Update database to actual state

Run this command from project root

	app/Console/cake setup update

In most situations it is enough to update db structure to actual state. It is recommended to run this command after each update from main repo.

## Editorconfig support

This app support editor config standard to unify code formating and you can enable it by setup proper extension for your editor or IDE. Read more [here](http://editorconfig.org/) Strongly recommended to use.
