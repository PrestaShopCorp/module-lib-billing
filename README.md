# prestashop-rbm-installer

Utility package to present data from psx configuration page.

## Installation

This package is available on [Packagist](https://packagist.org/packages/prestashop/prestashop-rbm-installer),
you can install it via [Composer](https://getcomposer.org).

```shell script
composer require prestashop/prestashop-rbm-installer
```
## Register as a service in your PSx container

Beforehand, you must have defined [PS Account services](https://github.com/PrestaShopCorp/prestashop-accounts-installer#register-as-a-service-in-your-psx-container-recommended)

Example :

```yaml
services:
  #####################
  # PS Billing

  ps_billings.facade:
    class: 'PrestaShop\PsBilling\Presenter\BillingPresenter'
    arguments:
      - '@rbm_example.module'
      - '@ps_accounts.facade'
      - '@rbm_example.context'

```

## How to use it 

### Presenter

For example in your main module's class `getContent` method.

```php
    Media::addJsDef($this->getService('ps_billings.facade')
        ->present([
            'sandbox' => true
            'billingEnv' => 'preprod',
            'logo' => '//yoururl.com/logo.svg',
            'tosLink' => 'https://yoururl.com/tos',
            'emailSupport' => 'youremail@yourdomain.ltd',
        ]),
    );
```