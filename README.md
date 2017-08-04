# ByteArk SDK for PHP

[![Packagist Version](https://img.shields.io/packagist/v/byteark/byteark-sdk-php.svg?style=flat)](https://packagist.org/packages/byteark/byteark-sdk-php)
[![Build Status](https://travis-ci.org/byteark/byteark-sdk-php.svg?branch=master)](https://travis-ci.org/byteark/byteark-sdk-php)


## Installation

You may install this SDK via [Composer](https://getcomposre.org)

    composer install byteark/byteark-sdk-php


## Using ByteArkV2UrlSigner class

First, create a ByteArkV2UrlSigner instance with `access_id` and `access_secret`.
(`access_id` is currently optional for ByteArk Fleet).

Then, call `sign` method with URL to sign,
Unix timestamp that the URL should expired, and sign options.

For sign options argument, must include `method`, which determines
which HTTP method is allowed, and may includes custom policies that appears in
[ByteArk Documentation](https://docs.byteark.com/article/secure-url-signature-v2/).

The following example will create a signed URL that allows only clients
within 103.253.0.0/16 subnet (because of `client_subnet16` custom policy)
to `GET` resource at `http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8`,
and allows the signature to be reused with any resources with URL that starts with
`http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/`
(because of `path_prefix` custom policy).

sign the URL that availabed until:

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
        'client_subnet16' => '103.253.0.0',
        'path_prefix' => '/video-objects/QDuxJm02TYqJ/',
    ]
);

/*
Got this url:
http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8
    ?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG
    &x_ark_auth_type=ark-v2
    &x_ark_client_subnet16=1
    &x_ark_expires=1514764800
    &x_ark_path_prefix=/video-objects/QDuxJm02TYqJ/
    &x_ark_signature=2bkwVFSu6CzW7KmzXkwDbA
*/
```

## Using RequestInfo class

After create a RequestInfo instance,
you may use `getCurrentUrl` method to help you get current URL,
and use `get` method to get some of these policy names:

* client_ip
* client_subnet16
* client_subnet24
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
        'client_subnet16' => $requestInfo->get('client_subnet16'),
        'path_prefix' => '/video-objects/QDuxJm02TYqJ/',
        'referer' => $request->getCurrentUrl()
    ]
);
```