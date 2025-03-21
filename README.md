# Laradev React - Development Setup

## About Laradev React

**Laradev-React** is a sample Laravel app that can be used as a skeleton for your next Laravel project.

It includes a basic setup for both the backend and frontend, with support for both **DDEV** and **Native** (PHP, Composer, etc running locally) development
environments.

## Features

1. Support for both **DDEV** and **Native** development environments.
2. React.js/Inertia frontend with Vite for faster development.
3. Tailwind CSS for styling.
4. SCSS support with PostCSS.

## Installation - Local Development

In order to start developing with **Laradev**, you will need to read the guide in
the [LOCAL-DEVELOPMENT.md](LOCAL-DEVELOPMENT.md) file.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

To contribute to the application, follow these steps:

1. Fork this repository.
2. Read the [CONTRIBUTING](CONTRIBUTING.md) file.
3. Create a branch: `git checkout -b <branch_name>`.
4. Make your changes and commit them: `git commit -m '<commit_message>'`
5. Push to the original branch: `git push origin <project_name>/<location>`
6. Create the pull request

### PHP code style - Laravel Pint

This application uses [Laravel Pint](https://laravel.com/docs/12.x/pint) in order to perform code-styling checks and fixes.

In order to run the styler, run :

```bash
./vendor/bin/pint --test -v # the --test will not do any changes, it will just output the changes needed

./vendor/bin/pint -v # this command will actually perform the code style changes
```

### Running tests

To run the tests, run the following command:

```bash
./vendor/bin/pest
```

To run the tests with coverage, run the following command:

```bash
XDEBUG_MODE=coverage ./vendor/bin/pest --coverage
```

To filter tests, use the `--filter` flag. For example:

```bash
./vendor/bin/pest --filter testName
```

### Releasing a new version

After you have committed your changes, create a new git tag:

```bash
git tag -a vx.y.z -m "This is a nice tag name"
```

(for the `x.y.z` version number, follow the [Semantic Versioning](https://semver.org/) guidelines).

Then, push the tag:

```bash
git push origin vx.y.z
```

Then, in the [GitHub Releases page](https://github.com/scify/laradev/releases), create a new Release *
*and correlate it with the tag that you just created.**

Also, don't forget to update the `CHANGELOG.md` file with the new version name, release date, and release notes.

## License

This project is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).

## Credits

- SciFY
    This project is developed and maintained by [SciFY](https://www.scify.org/) and is based on the [Laravel](https://laravel.com/) framework.
