# Omnipay: eWAY

**eWAY driver for the Omnipay PHP payment processing library**

[![Latest Stable Version](https://poser.pugx.org/omnipay/eway/version.png)](https://packagist.org/packages/omnipay/eway)
[![Total Downloads](https://poser.pugx.org/omnipay/eway/d/total.png)](https://packagist.org/packages/omnipay/eway)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 7.2+ and PHP 8.x. This package implements eWAY Rapid support for Omnipay 3.

[eWAY](https://www.eway.com.au/) was launched in Australia in 1998 and now operates payment gateways
in 8 countries.

## Installation

Omnipay is installed via [Composer](https://getcomposer.org/). To install, require `league/omnipay` and `omnipay/eway`:

```bash
composer require league/omnipay omnipay/eway
```

## Basic Usage

The following gateways are provided by this package:

* `Eway_Direct`: legacy XML API support. New integrations should avoid this gateway.
* `Eway_RapidDirect`: direct card processing for PCI-compliant or encrypted-card flows.
* `Eway_Rapid`: transparent redirect integration.
* `Eway_RapidShared`: hosted shared payment page integration.

The Rapid gateways in this package use the current eWAY REST endpoints and support the optional `apiVersion`
header documented by eWAY. If you want to pin the documented current API version explicitly:

```php
$gateway = Omnipay\Omnipay::create('Eway_RapidShared');
$gateway->setApiKey('Rapid API Key');
$gateway->setPassword('Rapid API Password');
$gateway->setApiVersion('47');
$gateway->setTestMode(true);
```

For shared-page flows, use `purchase()` to create the access code and redirect the customer, then
call `completePurchase()` after eWAY redirects the customer back with `?AccessCode=...`.

Supported shared-page options include:

* `cancelUrl`
* `logoUrl`
* `headerText`
* `footerText`
* `language`
* `customerReadOnly`
* `customView`
* `verifyCustomerPhone`
* `verifyCustomerEmail`

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Development

```bash
composer update
composer lint
composer analyse
composer test
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](https://stackoverflow.com/). Be sure to add the
[omnipay tag](https://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-eway/issues),
or better yet, fork the library and submit a pull request.
