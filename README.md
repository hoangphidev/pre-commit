# Laravel Pre-commit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hoangphi/pre-commit.svg?style=flat-square)](https://packagist.org/packages/hoangphi/pre-commit)
[![Total Downloads](https://img.shields.io/packagist/dt/hoangphi/pre-commit.svg?style=flat-square)](https://packagist.org/packages/hoangphi/pre-commit)

## Installation

```bash
composer require hoangphi/pre-commit
```

Publish the configuration:

```bash
php artisan vendor:publish --provider="HoangPhi\PreCommit\Providers\PreCommitServiceProvider" --tag=config
```

### Config pre-commit hooks
```bash
php artisan pre-commit:install
```

- Create PSR default config `phpcs.xml` in your root project.

```bash
php artisan pre-commit:create-phpcs
```

- Added all changed files to git stage and run test manually.

```bash
php artisan pre-commit:check
```
