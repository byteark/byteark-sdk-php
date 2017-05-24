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
                . '?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x_ark_auth_type=ark-v2'
                . '&x_ark_expires=1514764800'
                . '&x_ark_signature=cLwtn96a-YPY7jt8ZKSf_Q',
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
                'client_ip' => '103.253.132.65',
            ]
        );

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x_ark_auth_type=ark-v2'
                . '&x_ark_client_ip=1'
                . '&x_ark_expires=1514764800'
                . '&x_ark_signature=Gr9T_ZdHDy8l8CCPxpFjNg',
            $signedUrl
        );
    }

    public function testSignUrl_withCredentials_withSignClientIpOption_withPathPrefixOption_shouldGenerateValidSignedUrl()
    {
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

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x_ark_auth_type=ark-v2'
                . '&x_ark_client_ip=1'
                . '&x_ark_expires=1514764800'
                . '&x_ark_path_prefix=%2Fvideo-objects%2FQDuxJm02TYqJ%2F'
                . '&x_ark_signature=DPSPvsYjA2typby02i_cMw',
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
                'client_ip' => '103.253.132.65',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.68 Safari/537.36',
            ]
        );

        $this->assertEquals(
            'http://inox.qoder.byteark.com/video-objects/QDuxJm02TYqJ/playlist.m3u8'
                . '?x_ark_access_id=2Aj6Wkge4hi1ZYLp0DBG'
                . '&x_ark_auth_type=ark-v2'
                . '&x_ark_client_ip=1'
                . '&x_ark_expires=1514764800'
                . '&x_ark_signature=yYFkwZolbxCarOLHuKjD7w'
                . '&x_ark_user_agent=1',
            $signedUrl
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSignUrl_withMissingAccessId_shouldThrowInvalidArgumentException()
    {
        $signer = new ByteArkV2UrlSigner([
            'access_secret' => '2Aj6Wkge4hi1ZYLp0DBG',
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSignUrl_withMissingAccessSecret_shouldThrowInvalidArgumentException()
    {
        $signer = new ByteArkV2UrlSigner([
            'access_id' => '31sX5C0lcBiWuGPTzRszYvjxzzI3aCZjJi85ZyB7',
        ]);
    }
}
