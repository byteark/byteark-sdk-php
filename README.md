# ByteArk SDK for PHP

[![Packagist Version](https://img.shields.io/packagist/v/byteark/byteark-sdk-php.svg?style=flat)](https://packagist.org/packages/byteark/byteark-sdk-php)
[![Build Status](https://travis-ci.org/byteark/byteark-sdk-php.svg?branch=master)](https://travis-ci.org/byteark/byteark-sdk-php)

## Installation

You may install this SDK via [Composer](https://getcomposre.org)

    composer install byteark/byteark-sdk-php

## Usage

Now the only feature availabled is creating signed URL with ByteArk Signature Version 2

```php
$signer = new ByteArkV2UrlSigner([
    'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
    'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
]);

$signedUrl = $signer->sign(
    'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
    1514764800,
    [
        'client_ip' => '103.253.132.65',
        'path_prefix' => '/video-objects/QDuxJm02TYqJ/',
    ]
);

/*
Got this url:
http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8
    ?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG
    &x_ark_auth_type=ark-v2
    &x_ark_client_ip=1
    &x_ark_expires=1514764800
    &x_art_path_prefix=/video-objects/QDuxJm02TYqJ/
    &x_ark_signature=2bkwVFSu6CzW7KmzXkwDbA
*/
```

For more usage details, please visit [ByteArk Documentation](https://docs.byteark.com)
