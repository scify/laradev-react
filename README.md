<!-- omit in toc -->
# Laradev React - Development Setup

[![Tests](https://github.com/scify/laradev-react/actions/workflows/tests.yml/badge.svg)](https://github.com/scify/laradev-react/actions/workflows/tests.yml)
[![Linter](https://github.com/scify/laradev-react/actions/workflows/lint.yml/badge.svg)](https://github.com/scify/laradev-react/actions/workflows/lint.yml)
[![License](https://img.shields.io/badge/License-Apache_2.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)

<!-- omit in toc -->
## Table of Contents

- [About Laradev React](#about-laradev-react)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation - Local Development](#installation---local-development)
- [Changelog](#changelog)
- [Contributing](#contributing)
  - [PHP code style - Laravel Pint](#php-code-style---laravel-pint)
  - [Running tests](#running-tests)
  - [Code Scanning](#code-scanning)
  - [Git Hooks](#git-hooks)
- [Available Scripts](#available-scripts)
  - [Releasing a new version](#releasing-a-new-version)
- [Security](#security)
- [License](#license)
- [Credits](#credits)

## About Laradev React

**Laradev-React** is a sample Laravel app that can be used as a skeleton for your next Laravel project.

It includes a basic setup for both the backend and frontend, with support for both **DDEV** and **Native** (PHP, Composer, etc running locally) development environments.

## Features

1. Support for both **DDEV** and **Native** development environments
2. React.js/Inertia frontend with Vite for faster development
3. Tailwind CSS with shadcn/ui components
4. SCSS support with PostCSS
5. TypeScript support
6. Automated code formatting (PHP, JS/TS, SCSS)
7. Git hooks for code quality
8. Comprehensive test suite using Pest
9. Role-based authentication using Spatie Permissions
10. Dark mode support
11. Responsive design
12. GitHub Actions for CI/CD

## Tech Stack

- **Backend:**
  - Laravel 11.x
  - PHP 8.3+
  - MySQL/SQLite
  - Laravel Pint (Code Styling)
  - PHPStan (Static Analysis)
  - Pest (Testing)

- **Frontend:**
  - React 19
  - TypeScript
  - Tailwind CSS
  - shadcn/ui Components
  - Vite
  - ESLint + Prettier

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

### Code Scanning

This application uses [PHPStan](https://phpstan.org/) in order to perform code-scanning checks.

In order to run the code-scanner, run the following command:

```bash
./vendor/bin/phpstan analyse
```

### Git Hooks

The project includes pre-commit hooks that automatically format code. They're installed automatically with:

```bash
composer install
```

## Available Scripts

- `npm run dev` - Start Vite development server
- `npm run build` - Build for production
- `npm run lint` - Run ESLint
- `npm run lint:fix` - Fix ESLint issues
- `npm run types` - Check TypeScript types
- `composer test` - Run PHP tests
- `composer format` - Format PHP code
- `composer analyse` - Run static analysis

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

## Security

If you discover any security-related issues, please email `info[at]scify.org`, instead of using the issue tracker.

## License

This project is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).

## Credits

- SciFY
    This project is developed and maintained by [SciFY](https://www.scify.org/) and is based on the [Laravel](https://laravel.com/) framework.
