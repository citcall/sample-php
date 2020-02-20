Citcall Library for PHP 
============================

This is the PHP client library for use Citcall's API. To use this, you'll need a Citcall account and Your IP has been filtered in citcall system. See [citcall documentation][docs] for more information. This is currently a beta release.

Installation
------------

### Install with Composer
To install the PHP client library to your project, we recommend using [Composer](https://getcomposer.org/).

```bash
composer require citcall/api
```

> You don't need to clone this repository to use this library in your own projects. Use Composer to install it from Packagist.

If you're new to Composer, here are some resources that you may find useful:

* [Composer's Getting Started page](https://getcomposer.org/doc/00-intro.md) from Composer project's documentation.
* [A Beginner's Guide to Composer](https://scotch.io/tutorials/a-beginners-guide-to-composer) from the good people at ScotchBox.

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

Create a citcall with your API key:

```php
$citcall = new Citcall\Citcall(APIKEY);
```

Examples
--------

### Miscall OTP

To use [Citcall's Miscall Async API][docs_miscall_async] to Asynchronous miscall, call the `$citcall->miscall()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_miscall_async].

```php
$motp = $citcall->miscall([
	'msisdn' => MSISDN,
	'gateway' => GATEWAY
]);
```

If you want to able to do verify later use this example.

```php
$motp = $citcall->miscall([
	'msisdn' => MSISDN,
	'gateway' => GATEWAY,
	'valid_time' => TIME_VALID, //optional - valid time in seconds
	'limit_try' => LIMIT_TRY //optional - maximum attempt
	'callback_url' => CALLBACK_URL //Webhook URL where delivery status for the result will be posted (Overwrites your default account callback URL).
]);
```

The API response data can be accessed as array properties of the async_miscall. 

```php
print_r($motp);
```

### Callback Miscall OTP

You can configure your default callback URL for your account at our [Dashboard][dashboard] on API menu.
You can also overwrite the default callback URL on by specifying a different **callback_url** value in your API requests.

See this [Example](https://github.com/citcall/sample-php/blob/master/examples/callback_miscall.php) to use callback.

### SMS

To use [Citcall's SMS API][docs_sms] to send an SMS message, call the `$citcall->sms()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_sms].

```php
$sms = $citcall->sms([
	'senderid' => 'citcall',
	'msisdn' => MSISDN,
	'text' => 'Test message from the Citcall PHP'
]);
```

The API response data can be accessed as array properties of the sms. 

```php
print_r($sms);
```

### Callback SMS

You can configure your default callback URL for your account at our [Dashboard][dashboard] on API menu.
You can also overwrite the default callback URL on by specifying a different **callback_url** value in your API requests.

See this [Example](https://github.com/citcall/sample-php/blob/master/examples/callback_sms.php) to use callback.


### SMSOTP

To use [Citcall's SMSOTP API][docs_smsotp] to send an SMS message with OTP text, call the `$citcall->smsotp()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_smsotp].

```php
$smsotp = $citcall->smsotp([
	'senderid' => 'citcall',
	'msisdn' => MSISDN,
	'text' => 'Test message OTP from the Citcall PHP'
]);
```

The API response data can be accessed as array properties of the sms. 

```php
print_r($smsotp);
```

### Callback SMSOTP

You can configure your default callback URL for your account at our [Dashboard][dashboard] on API menu.
You can also overwrite the default callback URL on by specifying a different **callback_url** value in your API requests.

See this [Example](https://github.com/citcall/sample-php/blob/master/examples/callback_smsotp.php) to use callback.


### Verify OTP Code

To use [Citcall's Verify API][docs_verify] to verify OTP, call the `$citcall->verify()` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][docs_verify].

```php
$verify = $citcall->verify([
	'msisdn' => MSISDN,
	'trxid' => TRXID,
	'token' => TOKEN
]);
```

The API response data can be accessed as array properties of the verify_motp. 

```php
print_r($verify);
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
[docs_smsotp]: https://docs.citcall.com/#sms-otp
[docs_verify]: https://docs.citcall.com/#verify
[the repository]: https://github.com/citcall/sample-php
[dashboard]: https://dashboard.citcall.com
