<?php

namespace Tests\Unit\Signer;

use ByteArk\Signer\ByteArkV2UrlSigner;
use Tests\TestCase;

class ByteArkV2UrlSignerTest extends TestCase
{
    public function testSignUrl_withCredentials_shouldGenerateValidSignedUrl()
    {
        $signer = new ByteArkV2UrlSigner([
            'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
            'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
        ]);

        $signedUrl = $signer->sign(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
            1514764800
        );

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x-ark-access-id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x-ark-auth-type=ark-v2'
                . '&x-ark-expires=1514764800'
                . '&x-ark-signature=70bc2d9fde9af983d8ee3b7c64a49ffd',
            $signedUrl
        );
    }

    public function testSignUrl_withCredentials_withSignClientIpOption_shouldGenerateValidSignedUrl()
    {
        $signer = new ByteArkV2UrlSigner([
            'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
            'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
        ]);

        $signedUrl = $signer->sign(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
            1514764800,
            [
                'client-ip' => '103.253.132.65',
            ]
        );

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x-ark-access-id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x-ark-auth-type=ark-v2'
                . '&x-ark-expires=1514764800'
                . '&x-ark-sign-client-ip=1'
                . '&x-ark-signature=57aebae531c3d582029fc2440d3ff132',
            $signedUrl
        );
    }

    public function testSignUrl_withCredentials_withSignClientIpOption_withSignUserAgentOption_shouldGenerateValidSignedUrl()
    {
        $signer = new ByteArkV2UrlSigner([
            'access_id' => '2Aj6Wkge4hi1ZYLp0DBG',
            'access_secret' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
        ]);

        $signedUrl = $signer->sign(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8',
            1514764800,
            [
                'client-ip' => '103.253.132.65',
                'header-user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.68 Safari/537.36',
            ]
        );

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x-ark-access-id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x-ark-auth-type=ark-v2'
                . '&x-ark-expires=1514764800'
                . '&x-ark-sign-client-ip=1'
                . '&x-ark-sign-header-user-agent=1'
                . '&x-ark-signature=14a612b607e7aa4fe83ed0fab36dfff5',
            $signedUrl
        );
    }

    public function testSignUrl_withMissingAccessId_shouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $signer = new ByteArkV2UrlSigner([
            'access_secret' => '2Aj6Wkge4hi1ZYLp0DBG',
        ]);
    }

    public function testSignUrl_withMissingAccessSecret_shouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $signer = new ByteArkV2UrlSigner([
            'access_id' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
        ]);
    }
}
