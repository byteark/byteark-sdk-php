# ByteArk SDK for PHP

[![Packagist Version](https://img.shields.io/packagist/v/byteark/byteark-sdk-php.svg?style=flat)](https://packagist.org/packages/byteark/byteark-sdk-php)
[![Build Status](https://travis-ci.org/byteark/byteark-sdk-php.svg?branch=master)](https://travis-ci.org/byteark/byteark-sdk-php)

* [Installation](#installation)
* [Using ByteArkV2UrlSigner class](#using-byteArkv2urlsigner-class)
* [Using RequestInfo class](#using-requestinfo-class)
* [Example Projects](#example-projects)


## Installation

You may install this SDK via [Composer](https://getcomposre.org)

    composer require byteark/byteark-sdk-php


## Using ByteArkV2UrlSigner class

First, create a ByteArkV2UrlSigner instance with `access_id` and `access_secret`.
(`access_id` is currently optional for ByteArk Fleet).

Then, call `sign` method with URL to sign,
Unix timestamp that the URL should expired, and sign options.

For sign options argument, you may include `method`, which determines
which HTTP method is allowed (`GET` is the default is not determined),
and may includes custom policies that appears in
[ByteArk Documentation](https://docs.byteark.com/article/secure-url-signature-v2/).

The following example will create a signed URL that
allows to `GET` the resource within 1st January 2018:

```php
$signer = new \ByteArk\Signer\ByteArkV2UrlSigner([
    'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
    'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
]);

$signedUrl = $signer->sign(
    'http://sample.cdn.byteark.com/downloads/latest-software.zip',
    1514764800,
    [
        'method' => 'GET',
    ]
);
```

The following example will create a signed URL that:

* Allows to `GET` the resource within 1st January 2018 (bacause of `1514764800` timestamp).
* And allows only request from origin 'https://example.byteark.com' (from parameter 'origin')
* And allows the signature to be reused with any resources with URL that starts with
`http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/`
(because of `path_prefix` custom policy).

```php
$signer = new \ByteArk\Signer\ByteArkV2UrlSigner([
    'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
    'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
]);

$signedUrl = $signer->sign(
    'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
    1514764800,
    [
        'method' => 'GET',
        'origin' => 'https://example.byteark.com',
        'path_prefix' => '/video-objects/QDuxJm02TYqJ/',
    ]
);
```


## Using RequestInfo class

After create a RequestInfo instance,
you may use `getCurrentUrl` method to help you get current URL,
and use `get` method to get values for some of these policies:

* client_ip
* user_agent

For example:

```php
$requestInfo = new \ByteArk\Request\RequestInfo();
$signer = new \ByteArk\Signer\ByteArkV2UrlSigner([
    'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
    'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
]);

$signedUrl = $signer->sign(
    'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
    1514764800,
    [
        'method' => 'GET',
        'path_prefix' => '/video-objects/QDuxJm02TYqJ/',
        'referer' => $requestInfo->getCurrentUrl()
    ]
);
```


## Example Projects

You may try [the sample project](https://github.com/byteark/byteark-sdk-php-example)
that allows you to create signed URLs with simple web form.

## Change Log for 2019-11-04

* Add geo_allow / geo_block parameter for dynamic geo-blocking url by signurl
* Remove client_subnet16, client_subnet24 parameter support