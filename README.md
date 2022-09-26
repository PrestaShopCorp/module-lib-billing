# PrestaShop Billing Lib

Utility package to retrieve SasS App module context and more

[![Latest Stable Version](https://img.shields.io/packagist/v/prestashopcorp/module-lib-billing.svg?style=flat-square)](https://packagist.org/packages/prestashop/prestashopcorp/module-lib-billing) [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2.5-8892BF.svg?style=flat-square)](https://php.net/) [![Quality Control Status](https://img.shields.io/github/workflow/status/prestashopcorp/module-lib-billing/PsBilling%20Quality%20Control%20PHP?style=flat-square)](https://github.com/PrestaShopCorp/module-lib-billing/actions/workflows/billing-qc-php.yml)


## Installation

This package is available on [Packagist](https://packagist.org/packages/prestashopcorp/module-lib-billing),
you can install it via [Composer](https://getcomposer.org).

```shell script
composer require prestashopcorp/module-lib-billing
```

## Version Guidance

| Version | Status         | Packagist           -| Namespace    | Repo                | Docs                | PHP Version  |
|---------|----------------|----------------------|--------------|---------------------|---------------------|--------------|
| 1.x     | Security fixes | `module-lib-billing` | `PrestaShopCorp\Billing`     | [v1][lib-1-repo] | N/A                 | >=5.6        |
| 2.x     | Latest         | `module-lib-billing` | `PrestaShopCorp\Billing` | [v2][lib-2-repo] | N/A                 | >=7.2.5   |

[lib-1-repo]: https://github.com/PrestaShopCorp/module-lib-billing/tree/1.x
[lib-2-repo]: https://github.com/PrestaShopCorp/module-lib-billing

## Register as a service in your PSx container

Beforehand, you must have defined [PS Account services](https://github.com/PrestaShopCorp/prestashop-accounts-installer#register-as-a-service-in-your-psx-container-recommended)

Example :

```yaml
services:
  #####################
  # PS Billing
  ps_billings.context_wrapper:
    class: 'PrestaShopCorp\Billing\Wrappers\BillingContextWrapper'
    arguments:
      - '@ps_accounts.facade'
      - '@rbm_example.context'
      - true # if true you are in sandbox mode, if false or empty not in sandbox

  ps_billings.facade:
    class: 'PrestaShopCorp\Billing\Presenter\BillingPresenter'
    arguments:
      - '@ps_billings.context_wrapper'
      - '@rbm_example.module'

  # Remove this if you don't need BillingService
  ps_billings.service:
    class: PrestaShopCorp\Billing\Services\BillingService
    public: true
    arguments:
      - '@ps_billings.context_wrapper'
      - '@rbm_example.module'
```

## How to use it

### Presenter

For example in your main module's class `getContent` method.

```php
  // Load context for PsBilling
  $billingFacade = $this->getService('ps_billings.facade');

  // Remove this if you don't need to set an image
  $partnerLogo = $this->getLocalPath() . ' views/img/partnerLogo.png';

  // Billing
  Media::addJsDef($billingFacade->present([
      'logo' => $partnerLogo,
      'tosLink' => 'https://yoururl/',
      'privacyLink' => 'https://yoururl/',
      'emailSupport' => 'you@email',
  ]));
```

## Contribute

### Code style

```
php vendor/bin/php-cs-fixer fix
```

### Automatic tests

#### Install

Please follow theses steps to launch unit tests

```
# Needs to have wget, for OS without wget pleae see the official website (or just visit this link)
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;")
if [[ ${PHP_VERSION} -gt "72" ]]; then
  wget -O phpunit https://phar.phpunit.de/phpunit-9.phar
else
  wget -O phpunit https://phar.phpunit.de/phpunit-8.phar
fi

chmod +x phpunit

# Should display the version
./phpunit --version
```

#### Run

```
./phpunit tests
```
