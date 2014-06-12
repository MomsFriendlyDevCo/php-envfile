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
The `.env` file is a simple key/value file in the same form as a INI file.

	a=1
	b:2
	c = 3
	d : 4

Importing this into a PHP project is straightforward:

	include('envfile.php'); // <- Not needed if you use Composer

	$settings = envfile(); // If the defaults of .env (and .env.example for sanity checks) is ok

	$settings = encfile('.somewhere-else', '.somewhere-else.example'); // For specific file names

	$settings = encfile('.somewhere-else', '.somewhere-else.example', FALSE); // Dont fatally exit if there is a problem
