# ByteArk SDK for PHP

[![Packagist Version](https://img.shields.io/packagist/v/byteark/byteark-sdk-php.svg?style=flat)](https://packagist.org/packages/byteark/byteark-sdk-php)
[![Build Status](https://travis-ci.org/byteark/byteark-sdk-php.svg?branch=master)](https://travis-ci.org/byteark/byteark-sdk-php)


## Installation

You may install this SDK via [Composer](https://getcomposre.org)

    composer install byteark/byteark-sdk-php


## Using ByteArkV2UrlSigner class

Create a signer instance with `access_id` and `access_secret`,
then, call `sign` method with URL, expire timestamp, and custom policies.

For example:

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

You may use `getCurrentUrl` method to help you get current URL,
and `get` method to get some of these policy names:

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

For more usage details, please visit
[ByteArk Documentation](https://docs.byteark.com).
