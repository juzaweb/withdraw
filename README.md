# Juzaweb CMS Withdraw Module

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This is the Withdraw module for [Juzaweb CMS](https://juzaweb.com/cms). It provides features to manage withdrawal requests and withdrawal methods.

## Requirements

- PHP >= 8.2
- Laravel >= 11.0
- Juzaweb CMS >= 5.0

## Installation

You can install the module via composer:

```bash
composer require juzaweb/withdraw
```

After installation, the module should be visible in your Juzaweb CMS admin panel.

## Usage

This module provides the necessary structures to handle user withdrawals in Juzaweb CMS.

### Key Models

- **WithdrawMethod**: Defines the ways users can withdraw their funds. It supports custom fields and minimum withdrawal amounts.
- **Withdraw**: Represents a withdrawal request from a user. It keeps track of the amount, the chosen method, and the current status of the request.

Both models utilize Juzaweb Core traits (`HasAPI`, `UsedInFrontend`) which seamlessly integrate them into the CMS API and frontend operations.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
