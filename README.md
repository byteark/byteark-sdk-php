# ByteArk SDK for PHP

[![Packagist Version](https://img.shields.io/packagist/v/byteark/byteark-sdk-php.svg?style=flat)](https://packagist.org/packages/byteark/byteark-sdk-php)
[![Build Status](https://travis-ci.org/byteark/byteark-sdk-php.svg?branch=master)](https://travis-ci.org/byteark/byteark-sdk-php)

* [Installation](#installation)
* [Using ByteArkV2UrlSigner class](#using-byteArkv2urlsigner-class)
* [Usage for HLS](#usage-for-hls)
* [Options](#options)
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
    'https://example.cdn.byteark.com/path/to/file.png',
    1514764800,
    [
        'method' => 'GET',
    ]
);

/*
Output:
https://example.cdn.byteark.com/path/to/file.png
    ?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG
    &x_ark_auth_type=ark-v2
    &x_ark_expires=1514764800
    &x_ark_signature=OsBgZpn9LTAJowa0UUhlYQ
*/
```


## Usage for HLS

When signing URL for HLS, you have to choose common path prefix
and assign to `path_prefix` option is required,
since ByteArk will automatically create secured URLs for each segments
using the same options and signature.

For example, if your stream URL is `https://example.cdn.byteark.com/live/playlist.m3u8`,
you may use `/live/` as a path prefix.

```php
$signer = new \ByteArk\Signer\ByteArkV2UrlSigner([
    'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
    'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
]);

$signedUrl = $signer->sign(
    'https://example.cdn.byteark.com/live/playlist.m3u8',
    1514764800,
    [
        'method' => 'GET',
        'path_prefix' => '/live/',
    ]
);

echo $signedUrl;

/*
Output:
https://example.cdn.byteark.com/live/playlist.m3u8
    ?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG
    &x_ark_auth_type=ark-v2
    &x_ark_expires=1514764800
    &x_ark_path_prefix=%2Flive%2F
    &x_ark_signature=7JGsff2mBQEOoSYHTjxiVQ
*/
```


## Options

### ByteArkV2UrlSigner

| Option        | Required | Default | Description                                                               |
|---------------|----------|---------|---------------------------------------------------------------------------|
| access_id     | Required | -       | Access key ID for signing                                                 |
| acesss_secret | Required | -       | Access key secret for signing                                             |
| default_age   | -        | 900     | Default signed URL age (in seconds), if signing without expired date/time |

### ByteArkV2UrlSigner.sign(url, expires = null, options = [])

| Option      | Required | Default | Description                                                                                                                                                   |
|-------------|----------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| method      | -        | GET     | HTTP Method that allowed to use with the signed URL                                                                                                           |
| path_prefix | -        | -       | Path prefix that allowed to use with the signed URL (the same signing options and signature can be reuse with the


## Using RequestInfo class

(This is useful for legacy signing conditions, such as client_ip and user_agent).

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
