PHP-EnvFile
===========
PHP implementation of the [NodeJS envfile system](https://www.npmjs.org/package/envfile).

This system works by the dev placing a `.env` file in the root of a project.
That file is read for various config information which is imported into scope.
Its expected that that file is excluded from commits (via `.gitignore` or whatever) with every dev having their own `.env` file unique to them.

As an additional sanity measure its recommended that a `.env.example` file is placed in the same directory containing example settings.
If present that file will be scanned and the variables imported into `.env` compared to make sure no settings are missing.


Installation
------------
Grab the `envfile.php` file and drop it into your project.

Alternatively install via Composer:

	composer require hashbang/envfile


Usage
=====

The .env file
-------------
The `.env` file is a simple key/value file in the same form as a INI file.

	a=1
	b:2
	c = 3
	d : 4


Use within PHP
--------------
Importing this into a PHP project is straight-forward:

	include('envfile.php'); // <- Not needed if you use Composer

	$settings = envfile(); // If the defaults of .env (and .env.example for sanity checks) is ok

	$settings = encfile('.somewhere-else', '.somewhere-else.example'); // For specific file names

	$settings = encfile('.somewhere-else', '.somewhere-else.example', FALSE); // Dont fatally exit if there is a problem


Use within Bash
---------------
There is a helper script called `getval` which can extract a single value from a given `.env` file (or search directory). This can be used to extract variables into any external script:

	# Extract the DBUSER variable from .env
	DBUSER=`getval .env DBUSER`

	# Extract the DBUSER variable from an .env file located in the parent directory
	DBUSER=`getval .. DBUSER`


Use within Makefiles
--------------------
As with Bash use `getval` to extract settings one at a time:

	# Extract the DBUSER variable from .env
	DBUSER=$(shell getval .env DBUSER)

	# Extract the DBUSER variable from an .env file located in the parent directory
	DBUSER=$(shell getval .. DBUSER)


Use within Makefiles inside CI application folders
--------------------------------------------------
This example assumes that:

* A CodeIgniter project is active
* EnvFile is installed via Composer
* The main project Makefile is located in the `application` directory

A very specific example, but its what we use at [MFDC](http://mfdc.biz).

The following is an example Makefile:


	# Config details
	DBHOST=$(shell ../vendor/hashbang/envfile/getval .. DBHOST)
	DBUSER=$(shell ../vendor/hashbang/envfile/getval .. DBUSER)
	DBPASS=$(shell ../vendor/hashbang/envfile/getval .. DBPASS)
	DBDATABASE=$(shell ../vendor/hashbang/envfile/getval .. DBDATABASE)

	# MySQL config {{{
		ifdef DBPASS
			MYSQL=mysql ${DBDATABASE} -h"${DBHOST}" -u"${DBUSER}" -p"${DBPASS}"
		else
			MYSQL=mysql ${DBDATABASE} -h"${DBHOST}"-u"${DBUSER}"
		endif
	# }}}

	debug:
		echo "Connection Command: ${MYSQL}"

	database: database-clean
		@echo "Installing database schema..."
		${MYSQL} <../database/Schema.sql

	database-clean:
		@echo "Cleaning database [${DBDATABASE}]..."
		${MYSQL} -e 'DROP DATABASE IF EXISTS ${DBDATABASE}; CREATE DATABASE ${DBDATABASE}'
