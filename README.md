PHP-EnvFile
===========
PHP implementation of the [NodeJS envfile system](https://www.npmjs.org/package/envfile).

This system works by the dev placing a `.env` file in the root of a project.
That file is read for various config information which is imported into scope.
Its expected that that file is excluded from commits (via `.gitignore` or whatever) with every dev having their own `.env` file unique to them.

As an additional sanity measure its recommended that a `.env.example` file is placed in the same directory containing example settings.
If present that file will be scanned and the variables imported into `.env` compared to make sure no settings are missing.
