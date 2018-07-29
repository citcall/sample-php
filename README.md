Citcall Library for PHP 
============================

This is the PHP client library for use Citcall's API. To use this, you'll need a Citcall account and Your IP has been filtered in citcall system. See [citcall documentation][docs] for more information. This is currently a beta release.

Installation
------------

### Install with Composer
To install the PHP client library to your project, we recommend using [Composer](https://getcomposer.org/).

```bash
composer require citcall/api:dev-master
```

### Install source from GitHub
To install the source code:

	$ git clone git://github.com/citcall/sample-php.git

And include it in your scripts:

	require_once '/path/to/src/Citcall.php';

Usage
-----

If you're using Composer, make sure the autoloader is included in your project's bootstrap file:

```php
require_once "vendor/autoload.php";
```

Create a citcall with your userid and API key:

```php
$citcall = new Citcall\Citcall(USERID,APIKEY);
```

Examples
--------

### Synchronous miscall

To use [Citcall's Miscall Sync API][docs_miscall_sync] to send an Synchronous miscall, call the `$citcall->sync_miscall()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_miscall_sync].

```php
$sync_miscall = $citcall->sync_miscall([
	'msisdn' => MSISDN,
	'gateway' => GATEWAY
]);
```

The API response data can be accessed as array properties of the sync_miscall. 

```php
print_r($sync_miscall);
```

### Asynchronous miscall

To use [Citcall's Miscall Sync API][docs_miscall_async] to Asynchronous miscall, call the `$citcall->async_miscall()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_miscall_sync].

```php
$async_miscall = $citcall->async_miscall([
	'msisdn' => MSISDN,
	'gateway' => GATEWAY
]);
```

The API response data can be accessed as array properties of the async_miscall. 

```php
print_r($async_miscall);
```

### SMS

To use [Citcall's Miscall Sync API][docs_sms] to send an SMS message, call the `$citcall->sms()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_miscall_sync].

```php
$sms = $citcall->sms([
	'senderid' => 'citcall',
	'msisdn' => MSISDN,
	'text' => 'Test message from the Citcall PHP'
]);
```

The API response data can be accessed as array properties of the sms. 

```php
print_r($async_miscall);
```

Contribute
----------

1. Check for open issues or open a new issue for a feature request or a bug
2. Fork [the repository][] on Github to start making your changes to the
    `master` branch (or branch off of it)
3. Write a test which shows that the bug was fixed or that the feature works as expected
4. Send a pull request and bug us until We merge it

[docs]: https://docs.citcall.com
[docs_miscall_sync]: https://docs.citcall.com/#miscall
[docs_miscall_async]: https://docs.citcall.com/async/
[docs_sms]: https://docs.citcall.com/#sms
[the repository]: https://github.com/citcall/sample-php